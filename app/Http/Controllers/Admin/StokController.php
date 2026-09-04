<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use App\Support\AdminContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $stocks = WarehouseStock::with(['productVariant.product', 'warehouse'])
            ->whereHas('warehouse', fn($q) => $q->whereIn('store_id', $storeIds))
            ->orderBy('warehouse_stock_id')
            ->paginate(15);

        return view('Admin.stok.index', compact('stocks'));
    }

    public function update(Request $request, WarehouseStock $warehouseStock): RedirectResponse
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! in_array($warehouseStock->warehouse->store_id, $storeIds)) {
            abort(403);
        }

        $data = $request->validate([
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        $warehouseStock->update($data);

        return back()->with('success', 'Stok diperbarui.');
    }
}
