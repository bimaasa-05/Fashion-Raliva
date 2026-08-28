<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

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
            // Stok rusak/bermasalah = stok yang habis (0) atau di bawah ambang kritis.
            $items = WarehouseStock::with(['productVariant.product.category'])
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->where('jumlah_stok', '<=', 0)
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%' . $q . '%')))
                ->orderBy('jumlah_stok')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.stok-rusak.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'filters' => ['q' => $request->query('q')],
        ]);
    }
}
