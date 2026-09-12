<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PemindahanStokController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $transfers = collect();
        $otherWarehouses = collect();

        if ($warehouse) {
            $status = $request->query('status');
            $otherWarehouses = Warehouse::where('store_id', $warehouse->store_id)
                ->where('warehouse_id', '<>', $warehouse->warehouse_id)
                ->where('status', Warehouse::STATUS_AKTIF)
                ->orderBy('nama_gudang')
                ->get();

            $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester', 'items.productVariant.product'])
                ->where(fn ($q) => $q->where('from_warehouse_id', $warehouse->warehouse_id)
                    ->orWhere('to_warehouse_id', $warehouse->warehouse_id))
                ->when($status, fn ($q) => $q->where('status', $status))
                ->orderByDesc('diminta_pada')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.pemindahan.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'transfers' => $transfers,
            'otherWarehouses' => $otherWarehouses,
            'products' => $warehouse ? $this->getProductsForWarehouse($warehouse) : collect(),
            'filters' => ['status' => $request->query('status')],
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermission('warehouse.transfer')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.transfer) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'to_warehouse_id' => [
                'required',
                'different:'.$warehouse->warehouse_id,
                Rule::exists('warehouses', 'warehouse_id')
                    ->where('store_id', $warehouse->store_id)
                    ->where('status', Warehouse::STATUS_AKTIF),
            ],
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
        ], [
            'to_warehouse_id.required' => 'Gudang tujuan wajib dipilih.',
            'to_warehouse_id.different' => 'Gudang tujuan harus berbeda dari gudang asal.',
            'to_warehouse_id.exists' => 'Gudang tujuan tidak valid atau bukan gudang aktif pada toko yang sama.',
            'product_variant_id.required' => 'Produk wajib dipilih.',
            'product_variant_id.exists' => 'Produk tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);

        $stok = WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        $stokCukup = $stok && $stok->jumlah_stok >= $data['jumlah'];

        DB::transaction(function () use ($warehouse, $data) {
            $transfer = StockTransfer::create([
                'from_warehouse_id' => $warehouse->warehouse_id,
                'to_warehouse_id' => $data['to_warehouse_id'],
                'requested_by' => auth()->id(),
                'status' => StockTransfer::STATUS_REQUESTED,
                'diminta_pada' => now(),
            ]);

            StockTransferItem::create([
                'stock_transfer_id' => $transfer->stock_transfer_id,
                'product_variant_id' => $data['product_variant_id'],
                'jumlah' => $data['jumlah'],
            ]);
        });

        if (! $stokCukup) {
            return back()->with('toast', ['message' => 'Permintaan dibuat, tetapi stok gudang asal saat ini tidak mencukupi dan akan dicek saat persetujuan.', 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log(
            'stock.transfer',
            StockTransfer::class,
            $warehouse->warehouse_id,
            null,
            ['to_warehouse_id' => $data['to_warehouse_id'], 'product_variant_id' => $data['product_variant_id'], 'jumlah' => $data['jumlah']],
            sprintf('Pemindahan %d unit dari "%s" ke gudang tujuan.', $data['jumlah'], $warehouse->nama_gudang)
        );

        NotificationService::sendToRole(
            Role::ADMIN,
            Notification::TIPE_SISTEM,
            'Permintaan Pemindahan Stok',
            sprintf('Pemindahan %d unit diajukan dari gudang "%s".', $data['jumlah'], $warehouse->nama_gudang),
            auth()->id(),
            route('admin.koordinasi-gudang')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pemindahan Stok Diajukan', sprintf('Permintaan pemindahan %d unit berhasil dibuat.', $data['jumlah']), route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Permintaan pemindahan berhasil dibuat.', 'icon' => 'task_alt']);
    }

    public function terima(StockTransfer $stockTransfer)
    {
        if (! auth()->user()->hasPermission('warehouse.transfer')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.transfer) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        try {
            DB::transaction(function () use ($warehouse, $stockTransfer) {
                $transfer = StockTransfer::with('items')
                    ->where('stock_transfer_id', $stockTransfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->first();

                if (! $transfer || $transfer->to_warehouse_id !== $warehouse->warehouse_id) {
                    throw new \RuntimeException('Pemindahan ini bukan untuk gudang aktif Anda.');
                }

                if (! $transfer->canTransitionTo(StockTransfer::STATUS_RECEIVED)) {
                    throw new \RuntimeException('Pemindahan tidak dapat diterima pada status ini.');
                }

                foreach ($transfer->items as $item) {
                    $target = WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if ($target) {
                        $updated = WarehouseStock::where('warehouse_stock_id', $target->warehouse_stock_id)
                            ->increment('jumlah_stok', (int) $item->jumlah);

                        if ($updated === 0) {
                            throw new \RuntimeException('Gagal menambah stok gudang tujuan.');
                        }
                    } else {
                        WarehouseStock::create([
                            'warehouse_id' => $warehouse->warehouse_id,
                            'product_variant_id' => $item->product_variant_id,
                            'jumlah_stok' => $item->jumlah,
                        ]);
                    }

                    StockMovement::create([
                        'warehouse_id' => $warehouse->warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
                        'jumlah' => $item->jumlah,
                        'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                        'sumber_id' => $transfer->stock_transfer_id,
                        'alasan' => 'Pemindahan stok masuk',
                        'dibuat_oleh' => auth()->id(),
                    ]);
                }

                $affected = StockTransfer::where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->update([
                        'status' => StockTransfer::STATUS_RECEIVED,
                        'diterima_pada' => now(),
                    ]);

                if ($affected === 0) {
                    throw new \RuntimeException('Gagal memperbarui status pemindahan.');
                }
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => 'Gagal menerima pemindahan.', 'icon' => 'error']);
        }

        ActivityLogger::log(
            'stock.transfer.received',
            StockTransfer::class,
            $stockTransfer->stock_transfer_id,
            null,
            ['transfer_id' => $stockTransfer->stock_transfer_id],
            sprintf('Pemindahan #TRF-%d diterima di gudang "%s".', $stockTransfer->stock_transfer_id, $warehouse->nama_gudang)
        );

        NotificationService::sendToRole(
            Role::ADMIN,
            Notification::TIPE_SISTEM,
            'Pemindahan Stok Diterima',
            sprintf('Pemindahan #TRF-%d diterima di gudang "%s".', $stockTransfer->stock_transfer_id, $warehouse->nama_gudang),
            auth()->id(),
            route('admin.koordinasi-gudang')
        );

        if ($stockTransfer->requested_by) {
            Notification::create([
                'user_id' => $stockTransfer->requested_by,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Pemindahan Stok Diterima',
                'pesan' => sprintf('Pemindahan #TRF-%d telah diterima di gudang "%s".', $stockTransfer->stock_transfer_id, $warehouse->nama_gudang),
                'url' => route('gudang.pemindahan'),
            ]);
        }

        return back()->with('toast', ['message' => 'Pemindahan berhasil diterima.', 'icon' => 'task_alt']);
    }

    private function getProductsForWarehouse($warehouse)
    {
        return WarehouseStock::with(['productVariant.product'])
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->where('jumlah_stok', '>', 0)
            ->orderBy('product_variant_id')
            ->get();
    }
}
