<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $stocks = WarehouseStock::with(['productVariant.product', 'warehouse'])
            ->whereHas('warehouse', fn($q) => $q->whereIn('store_id', $storeIds))
            ->orderByRaw("CASE WHEN jumlah_stok <= stok_minimum THEN 0 ELSE 1 END")
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('Admin.stok.index', compact('stocks'));
    }
}
