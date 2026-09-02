<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockDamage;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokRusakController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $items = collect();
        if ($warehouse) {
            $q = $request->query('q');
            $items = WarehouseStock::with(['productVariant.product.category'])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->where('jumlah_stok', '<=', 0)
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%'.$q.'%')))
                ->orderBy('jumlah_stok')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.stok-rusak.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'products' => $warehouse ? $this->getProductsForWarehouse($warehouse) : collect(),
            'filters' => ['q' => $request->query('q')],
        ]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermission('warehouse.damage')) {
            abort(403, 'Anda tidak memiliki izin (warehouse.damage) untuk melakukan tindakan ini.');
        }

        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'jumlah_rusak' => 'required|integer|min:1',
            'alasan' => 'nullable|string|max:500',
        ], [
            'product_variant_id.required' => 'Produk wajib dipilih.',
            'product_variant_id.exists' => 'Produk tidak valid.',
            'jumlah_rusak.required' => 'Jumlah rusak wajib diisi.',
            'jumlah_rusak.integer' => 'Jumlah rusak harus berupa angka.',
            'jumlah_rusak.min' => 'Jumlah rusak minimal 1.',
        ]);

        $stok = WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        if (! $stok || $stok->jumlah_stok < $data['jumlah_rusak']) {
            return back()->with('toast', ['message' => 'Stok tidak mencukupi untuk dilaporkan rusak.', 'icon' => 'gpp_maybe']);
        }

        DB::transaction(function () use ($warehouse, $data, $stok) {
            StockDamage::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $data['product_variant_id'],
                'jumlah_rusak' => $data['jumlah_rusak'],
                'alasan' => $data['alasan'],
                'dibuat_oleh' => auth()->id(),
            ]);

            $stok->decrement('jumlah_stok', $data['jumlah_rusak']);

            StockMovement::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $data['product_variant_id'],
                'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                'jumlah' => $data['jumlah_rusak'],
                'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                'alasan' => $data['alasan'] ?? 'Stok rusak/dihapus',
                'dibuat_oleh' => auth()->id(),
            ]);
        });

        ActivityLogger::log(
            'stock.damage',
            WarehouseStock::class,
            $warehouse->warehouse_id,
            null,
            ['product_variant_id' => $data['product_variant_id'], 'jumlah_rusak' => $data['jumlah_rusak']],
            sprintf('Laporan stok rusak %d unit di gudang "%s".', $data['jumlah_rusak'], $warehouse->nama_gudang)
        );

        return back()->with('toast', ['message' => 'Stok rusak berhasil dilaporkan.', 'icon' => 'task_alt']);
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
