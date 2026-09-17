<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        if (! in_array($status, ['semua', 'aktif', 'verifikasi', 'nonaktif'], true)) {
            $status = 'semua';
        }

        $q = $request->query('q');
        $q = is_string($q) ? trim($q) : '';

        $stats = [
            'semua' => Supplier::count(),
            'aktif' => Supplier::where('status', 'aktif')->count(),
            'verifikasi' => Supplier::where('status', 'verifikasi')->count(),
            'nonaktif' => Supplier::where('status', 'nonaktif')->count(),
        ];

        $suppliers = Supplier::query()
            ->with('bahans')
            ->when($status !== 'semua', fn ($query) => $query->where('status', $status))
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('nama_supplier', 'like', "%{$q}%")
                    ->orWhere('kontak', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('kota', 'like', "%{$q}%")
                    ->orWhere('jenis', 'like', "%{$q}%")
                    ->orWhereHas('bahans', fn ($bahan) => $bahan->where('nama_bahan', 'like', "%{$q}%"));
            }))
            ->orderBy('nama_supplier')
            ->paginate(20)
            ->withQueryString();

        return view('SuperAdmin.supplier.index', [
            'suppliers' => $suppliers,
            'stats' => $stats,
            'activeStatus' => $status,
            'q' => $q,
        ]);
    }
}