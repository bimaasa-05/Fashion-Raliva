<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $items = collect();
        if ($warehouse) {
            $q = $request->query('q');
            $items = StockMovement::with(['productVariant.product', 'creator'])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->whereIn('tipe_pergerakan', [StockMovement::TIPE_MASUK, StockMovement::TIPE_MUTASI_MASUK])
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%'.$q.'%')))
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.barang-masuk.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'products' => $warehouse ? $this->getProductsForWarehouse($warehouse) : collect(),
            'filters' => ['q' => $request->query('q')],
        ]);
    }

    public function store(Request $request)
    {
        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,product_variant_id',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'nullable|string|max:500',
        ], [
            'product_variant_id.required' => 'Produk wajib dipilih.',
            'product_variant_id.exists' => 'Produk tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () use ($warehouse, $data) {
            WarehouseStock::updateOrCreate(
                ['warehouse_id' => $warehouse->warehouse_id, 'product_variant_id' => $data['product_variant_id']],
                ['jumlah_stok' => DB::raw('jumlah_stok + '.$data['jumlah'])]
            );

            StockMovement::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $data['product_variant_id'],
                'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                'jumlah' => $data['jumlah'],
                'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                'alasan' => $data['alasan'] ?? 'Barang masuk manual',
                'dibuat_oleh' => auth()->id(),
            ]);
        });

        ActivityLogger::log(
            'stock.in',
            WarehouseStock::class,
            $warehouse->warehouse_id,
            null,
            ['product_variant_id' => $data['product_variant_id'], 'jumlah' => $data['jumlah']],
            sprintf('Barang masuk %d unit ke gudang "%s".', $data['jumlah'], $warehouse->nama_gudang)
        );

        return back()->with('toast', ['message' => 'Barang masuk berhasil dicatat.', 'icon' => 'task_alt']);
    }

    private function getProductsForWarehouse($warehouse)
    {
        return WarehouseStock::with(['productVariant.product'])
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->orderBy('product_variant_id')
            ->get();
    }
}
