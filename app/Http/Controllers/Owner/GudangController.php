<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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

        return view('Owner.gudang.index', compact('warehouses', 'summary'));
    }
}
