<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class StokController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return view('Gudang.stok.index', [
                'warehouses' => $warehouses,
                'warehouse' => null,
                'products' => collect(),
                'categories' => collect(),
                'filters' => ['q' => null, 'kategori' => null, 'status' => null, 'sort' => 'terbaru'],
            ]);
        }

        $warehouseId = $warehouse->warehouse_id;

        $q = $request->query('q');
        $kategori = $request->query('kategori');
        $status = $request->query('status');
        $sort = $request->query('sort', 'terbaru');

        // Ambil varian yang punya stok di gudang aktif, lalu group ke produk.
        $variantIds = WarehouseStock::where('warehouse_id', $warehouseId)
            ->pluck('product_variant_id');

        $query = Product::with([
            'category',
            'variants' => function ($vq) use ($variantIds, $warehouseId) {
                $vq->whereIn('product_variant_id', $variantIds)
                    ->with(['warehouseStocks' => function ($sq) use ($warehouseId) {
                        $sq->where('warehouse_id', $warehouseId);
                    }]);
            },
        ])
            ->whereHas('variants', function ($vq) use ($variantIds) {
                $vq->whereIn('product_variant_id', $variantIds);
            })
            ->where('store_id', $warehouse->store_id);

        if ($q) {
            $query->where('nama_produk', 'like', '%'.$q.'%');
        }
        if ($kategori) {
            $query->whereHas('category', fn ($cq) => $cq->where('nama_kategori', $kategori));
        }

        $products = $query->get();

        // Hitung stok agregat & status per produk, lalu filter status & sort.
        $products = $products->map(function ($product) {
            $total = $product->variants->sum(fn ($v) => $v->warehouseStocks->sum('jumlah_stok'));
            $min = $product->variants->min(fn ($v) => $v->warehouseStocks->min('stok_minimum')) ?? 0;
            $status = $this->productStatus($total, $min);

            return (object) [
                'produk' => $product,
                'total_stok' => $total,
                'stok_minimum' => $min,
                'status' => $status,
                'sku' => $product->variants->first()?->sku ?? '-',
                'hpp' => $product->variants->first()?->harga ?? $product->harga_dasar,
                'harga_jual' => $product->harga_dasar,
                'variasi' => $product->variants->map(fn ($v) => trim(($v->warna ?? '').' '.($v->ukuran ?? '')))->filter()->implode(', '),
                'updated_at' => $product->variants->max(fn ($v) => $v->warehouseStocks->max('updated_at')),
            ];
        });

        if ($status && $status !== 'semua') {
            $products = $products->where('status', $status);
        }

        $order = $sort === 'nama' ? 'nama_produk' : ($sort === 'stok_kecil' ? 'total_stok' : ($sort === 'stok_besar' ? 'total_stok' : 'updated_at'));
        $products = $sort === 'stok_kecil'
            ? $products->sortBy('total_stok')
            : ($sort === 'stok_besar' ? $products->sortByDesc('total_stok') : ($sort === 'nama' ? $products->sortBy('produk.nama_produk') : $products->sortByDesc('updated_at')));

        $categories = Category::whereIn('category_id', $products->pluck('produk.category_id')->filter()->unique())->pluck('nama_kategori');

        return view('Gudang.stok.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'products' => $products->values(),
            'categories' => $categories,
            'filters' => ['q' => $q, 'kategori' => $kategori, 'status' => $status, 'sort' => $sort],
        ]);
    }

    private function productStatus(int $total, int $min): string
    {
        if ($total <= 0) {
            return 'habis';
        }
        if ($total <= $min) {
            return $total <= (int) round($min / 2) ? 'kritis' : 'menipis';
        }

        return 'aman';
    }
}
