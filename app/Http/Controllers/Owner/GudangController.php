<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $warehouses = Warehouse::with('staff')
            ->where('store_id', $storeId)
            ->get();

        $summary = [
            'total' => $warehouses->count(),
            'unit' => 0,
            'menipis' => 0,
            'kapasitas' => $warehouses->avg('kapasitas') ?? 0,
        ];

        $menungguPersetujuan = $storeId
            ? StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester', 'items.productVariant.product'])
                ->where('status', StockTransfer::STATUS_REQUESTED)
                ->whereHas('fromWarehouse', fn ($query) => $query->where('store_id', $storeId))
                ->orderByDesc('stock_transfer_id')
                ->limit(10)
                ->get()
            : collect();

        return view('Owner.gudang.index', compact('warehouses', 'summary', 'menungguPersetujuan'));
    }
}
