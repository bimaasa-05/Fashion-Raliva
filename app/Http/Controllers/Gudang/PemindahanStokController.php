<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;

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
            'filters' => ['status' => $request->query('status')],
        ]);
    }
}
