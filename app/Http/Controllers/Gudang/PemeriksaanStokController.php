<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class PemeriksaanStokController extends Controller
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
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%' . $q . '%')))
                ->orderBy('jumlah_stok')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.pemeriksaan.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'filters' => ['q' => $request->query('q')],
        ]);
    }
}
