<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
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
                ->whereIn('tipe_pergerakan', [StockMovement::TIPE_KELUAR, StockMovement::TIPE_MUTASI_KELUAR])
                ->when($q, fn ($query) => $query->whereHas('productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', '%' . $q . '%')))
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('Gudang.barang-keluar.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'items' => $items,
            'filters' => ['q' => $request->query('q')],
        ]);
    }
}
