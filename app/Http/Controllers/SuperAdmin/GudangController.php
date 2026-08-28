<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class GudangController extends Controller
{
    public function index()
    {
        $query = Warehouse::with(['store:store_id,nama_toko'])
            ->withCount('stocks')
            ->orderByDesc('updated_at');

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $warehouses = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.gudang.index', [
            'warehouses' => $warehouses,
        ]);
    }
}
