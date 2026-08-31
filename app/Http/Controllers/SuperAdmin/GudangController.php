<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class GudangController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['store:store_id,nama_toko'])
            ->withCount('stocks')
            ->orderByDesc('updated_at')
            ->get();

        $stats = [
            'semua' => $warehouses->count(),
            'aktif' => $warehouses->where('status', 'aktif')->count(),
            'nonaktif' => $warehouses->where('status', 'nonaktif')->count(),
        ];

        return view('SuperAdmin.gudang.index', [
            'warehouses' => $warehouses,
            'stats' => $stats,
        ]);
    }
}
