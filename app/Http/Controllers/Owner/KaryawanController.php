<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StoreStaff;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $staff = StoreStaff::with('user')
            ->where('store_id', $storeId)
            ->orderByDesc('store_staff_id')
            ->paginate(15)
            ->withQueryString();

        $all = StoreStaff::where('store_id', $storeId)->get();
        $summary = [
            'total' => $all->count(),
            'admin' => $all->where('role', 'admin')->count(),
            'produksi_gudang' => $all->whereIn('role', ['produksi', 'gudang'])->count(),
            'nonaktif' => $all->where('status', 'nonaktif')->count(),
        ];

        $roleLabel = [
            'admin' => 'Admin Toko',
            'produksi' => 'Produksi',
            'gudang' => 'Gudang',
        ];

        $storeName = \App\Support\OwnerContext::currentStore()?->nama_toko ?? '-';

        return view('Owner.karyawan.index', compact('staff', 'summary', 'roleLabel', 'storeName'));
    }
}
