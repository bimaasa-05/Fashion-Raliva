<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'to_warehouse_id' => 'required|exists:warehouses,warehouse_id|different:'.$warehouse->warehouse_id,
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
        ], [
            'to_warehouse_id.required' => 'Gudang tujuan wajib dipilih.',
            'to_warehouse_id.exists' => 'Gudang tujuan tidak valid.',
            'to_warehouse_id.different' => 'Gudang tujuan harus berbeda dari gudang asal.',
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

            WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
                ->where('product_variant_id', $data['product_variant_id'])
                ->decrement('jumlah_stok', $data['jumlah']);

            StockMovement::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $data['product_variant_id'],
                'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
                'jumlah' => $data['jumlah'],
                'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                'sumber_id' => $transfer->stock_transfer_id,
                'alasan' => $data['catatan'] ?? 'Pemindahan stok keluar',
                'dibuat_oleh' => auth()->id(),
            ]);

            WarehouseStock::updateOrCreate(
                ['warehouse_id' => $data['to_warehouse_id'], 'product_variant_id' => $data['product_variant_id']],
                ['jumlah_stok' => DB::raw('jumlah_stok + '.$data['jumlah'])]
            );

            StockMovement::create([
                'warehouse_id' => $data['to_warehouse_id'],
                'product_variant_id' => $data['product_variant_id'],
                'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
                'jumlah' => $data['jumlah'],
                'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                'sumber_id' => $transfer->stock_transfer_id,
                'alasan' => $data['catatan'] ?? 'Pemindahan stok masuk',
                'dibuat_oleh' => auth()->id(),
            ]);
        });

        ActivityLogger::log(
            'stock.transfer',
            StockTransfer::class,
            $warehouse->warehouse_id,
            null,
            ['to_warehouse_id' => $data['to_warehouse_id'], 'product_variant_id' => $data['product_variant_id'], 'jumlah' => $data['jumlah']],
            sprintf('Pemindahan %d unit dari "%s" ke gudang tujuan.', $data['jumlah'], $warehouse->nama_gudang)
        );

        return back()->with('toast', ['message' => 'Permintaan pemindahan berhasil dibuat.', 'icon' => 'task_alt']);
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
