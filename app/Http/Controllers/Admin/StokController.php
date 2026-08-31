<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $stocks = WarehouseStock::with(['productVariant.product', 'warehouse'])
            ->orderBy('warehouse_stock_id')
            ->paginate(15);

        return view('Admin.stok.index', compact('stocks'));
    }

    public function update(Request $request, WarehouseStock $warehouseStock): RedirectResponse
    {
        $data = $request->validate([
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        $warehouseStock->update($data);

        return back()->with('success', 'Stok diperbarui.');
    }
}
