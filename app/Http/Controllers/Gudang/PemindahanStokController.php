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

        if (! $stok || $stok->jumlah_stok < $data['jumlah']) {
            return back()->with('toast', ['message' => 'Stok tidak mencukupi untuk dipindahkan.', 'icon' => 'gpp_maybe']);
        }

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

        ActivityLogger::log(
            'stock.transfer',
            StockTransfer::class,
            $warehouse->warehouse_id,
            null,
            ['to_warehouse_id' => $data['to_warehouse_id'], 'product_variant_id' => $data['product_variant_id'], 'jumlah' => $data['jumlah']],
            sprintf('Mengajukan pemindahan %d unit dari "%s" (menunggu persetujuan gudang tujuan).', $data['jumlah'], $warehouse->nama_gudang)
        );

        NotificationService::sendToRole(
            Role::ADMIN,
            Notification::TIPE_SISTEM,
            'Permintaan Pemindahan Stok',
            sprintf('Pemindahan %d unit diajukan dari gudang "%s", menunggu persetujuan.', $data['jumlah'], $warehouse->nama_gudang),
            auth()->id(),
            route('admin.koordinasi-gudang')
        );
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pemindahan Stok Diajukan', sprintf('Permintaan pemindahan %d unit berhasil dibuat, menunggu persetujuan gudang tujuan.', $data['jumlah']), route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Permintaan pemindahan berhasil dibuat, menunggu persetujuan.', 'icon' => 'task_alt']);
    }

    public function approve(Request $request, StockTransfer $transfer)
    {
        if (! auth()->user()->hasPermission('warehouse.transfer')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.transfer) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        if ((int) $transfer->to_warehouse_id !== (int) $warehouse->warehouse_id) {
            return back()->with('toast', ['message' => 'Hanya gudang tujuan yang dapat menyetujui pemindahan.', 'icon' => 'gpp_maybe']);
        }

        if ($transfer->status !== StockTransfer::STATUS_REQUESTED) {
            return back()->with('toast', ['message' => 'Pemindahan tidak berstatus diajukan.', 'icon' => 'gpp_maybe']);
        }

        try {
            DB::transaction(function () use ($transfer, $warehouse) {
                $locked = StockTransfer::with('items')
                    ->where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($locked->status !== StockTransfer::STATUS_REQUESTED) {
                    throw new \RuntimeException('Status pemindahan sudah berubah.');
                }

                foreach ($locked->items as $item) {
                    $stok = WarehouseStock::where('warehouse_id', $locked->from_warehouse_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $stok || $stok->jumlah_stok < $item->jumlah) {
                        throw new \RuntimeException('Stok gudang asal tidak mencukupi untuk pemindahan ini.');
                    }
                }

                foreach ($locked->items as $item) {
                    WarehouseStock::where('warehouse_id', $locked->from_warehouse_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->decrement('jumlah_stok', $item->jumlah);

                    StockMovement::create([
                        'warehouse_id' => $locked->from_warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
                        'jumlah' => $item->jumlah,
                        'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                        'sumber_id' => $locked->stock_transfer_id,
                        'alasan' => 'Pemindahan stok keluar (disetujui)',
                        'dibuat_oleh' => auth()->id(),
                    ]);
                }

                $locked->update(['status' => StockTransfer::STATUS_APPROVED, 'approved_by' => auth()->id()]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('stock.transfer.approve', StockTransfer::class, $transfer->stock_transfer_id, ['status' => StockTransfer::STATUS_REQUESTED], ['status' => StockTransfer::STATUS_APPROVED], sprintf('Menyetujui pemindahan ke "%s".', $warehouse->nama_gudang));
        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM, 'Pemindahan Disetujui', sprintf('Pemindahan ke gudang "%s" disetujui.', $warehouse->nama_gudang), auth()->id(), route('admin.koordinasi-gudang'));
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pemindahan Disetujui', 'Pemindahan stok disetujui, menunggu penerimaan barang.', route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Pemindahan disetujui, stok keluar dicatat.', 'icon' => 'task_alt']);
    }

    public function receive(Request $request, StockTransfer $transfer)
    {
        if (! auth()->user()->hasPermission('warehouse.transfer')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.transfer) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        if ((int) $transfer->to_warehouse_id !== (int) $warehouse->warehouse_id) {
            return back()->with('toast', ['message' => 'Hanya gudang tujuan yang dapat menerima pemindahan.', 'icon' => 'gpp_maybe']);
        }

        if ($transfer->status !== StockTransfer::STATUS_APPROVED) {
            return back()->with('toast', ['message' => 'Pemindahan belum disetujui.', 'icon' => 'gpp_maybe']);
        }

        try {
            DB::transaction(function () use ($transfer, $warehouse) {
                $locked = StockTransfer::with('items')
                    ->where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($locked->status !== StockTransfer::STATUS_APPROVED) {
                    throw new \RuntimeException('Status pemindahan sudah berubah.');
                }

                foreach ($locked->items as $item) {
                    WarehouseStock::updateOrCreate(
                        ['warehouse_id' => $locked->to_warehouse_id, 'product_variant_id' => $item->product_variant_id],
                        ['jumlah_stok' => DB::raw('jumlah_stok + '.$item->jumlah)]
                    );

                    StockMovement::create([
                        'warehouse_id' => $locked->to_warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
                        'jumlah' => $item->jumlah,
                        'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                        'sumber_id' => $locked->stock_transfer_id,
                        'alasan' => 'Penerimaan pemindahan stok',
                        'dibuat_oleh' => auth()->id(),
                    ]);
                }

                $locked->update(['status' => StockTransfer::STATUS_RECEIVED, 'diterima_pada' => now()]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('stock.transfer.receive', StockTransfer::class, $transfer->stock_transfer_id, ['status' => StockTransfer::STATUS_APPROVED], ['status' => StockTransfer::STATUS_RECEIVED], sprintf('Menerima pemindahan di "%s".', $warehouse->nama_gudang));
        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM, 'Pemindahan Diterima', sprintf('Pemindahan ke gudang "%s" telah diterima.', $warehouse->nama_gudang), auth()->id(), route('admin.koordinasi-gudang'));
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pemindahan Diterima', 'Barang pemindahan stok telah diterima.', route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Pemindahan diterima, stok masuk dicatat.', 'icon' => 'task_alt']);
    }

    public function cancel(Request $request, StockTransfer $transfer)
    {
        if (! auth()->user()->hasPermission('warehouse.transfer')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.transfer) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        if ((int) $transfer->from_warehouse_id !== (int) $warehouse->warehouse_id) {
            return back()->with('toast', ['message' => 'Hanya gudang asal yang dapat membatalkan pemindahan.', 'icon' => 'gpp_maybe']);
        }

        if (! in_array($transfer->status, [StockTransfer::STATUS_REQUESTED, StockTransfer::STATUS_APPROVED], true)) {
            return back()->with('toast', ['message' => 'Pemindahan yang sudah diterima tidak dapat dibatalkan.', 'icon' => 'gpp_maybe']);
        }

        try {
            DB::transaction(function () use ($transfer) {
                $locked = StockTransfer::with('items')
                    ->where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! in_array($locked->status, [StockTransfer::STATUS_REQUESTED, StockTransfer::STATUS_APPROVED], true)) {
                    throw new \RuntimeException('Status pemindahan sudah berubah.');
                }

                if ($locked->status === StockTransfer::STATUS_APPROVED) {
                    foreach ($locked->items as $item) {
                        WarehouseStock::updateOrCreate(
                            ['warehouse_id' => $locked->from_warehouse_id, 'product_variant_id' => $item->product_variant_id],
                            ['jumlah_stok' => DB::raw('jumlah_stok + '.$item->jumlah)]
                        );

                        StockMovement::create([
                            'warehouse_id' => $locked->from_warehouse_id,
                            'product_variant_id' => $item->product_variant_id,
                            'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
                            'jumlah' => $item->jumlah,
                            'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                            'sumber_id' => $locked->stock_transfer_id,
                            'alasan' => 'Pengembalian stok atas pembatalan pemindahan',
                            'dibuat_oleh' => auth()->id(),
                        ]);
                    }
                }

                $locked->update(['status' => StockTransfer::STATUS_CANCELLED]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('stock.transfer.cancel', StockTransfer::class, $transfer->stock_transfer_id, null, ['status' => StockTransfer::STATUS_CANCELLED], 'Membatalkan pemindahan stok.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pemindahan Dibatalkan', 'Permintaan pemindahan stok dibatalkan.', route('gudang.dashboard'));

        return back()->with('toast', ['message' => 'Pemindahan dibatalkan.', 'icon' => 'task_alt']);
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
