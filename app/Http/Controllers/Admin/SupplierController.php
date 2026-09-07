<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $suppliers = Supplier::when($q, fn ($qb) => $qb->where('nama_supplier', 'like', "%{$q}%"))
            ->orderBy('nama_supplier')
            ->paginate(12);

        $stats = [
            'total' => $suppliers->total(),
            'aktif' => Supplier::where('status', 'aktif')->count(),
            'menunggu' => Supplier::where('status', 'verifikasi')->count(),
            'kota' => Supplier::distinct('kota')->count('kota'),
        ];

        return view('Admin.supplier.supplier', compact('suppliers', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:120',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:80',
            'jenis' => 'nullable|string|max:30',
            'catatan' => 'nullable|string|max:1000',
            'status' => 'required|in:aktif,nonaktif,verifikasi',
        ]);

        Supplier::create($data);

        return back()->with('success', 'Supplier ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:120',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:80',
            'jenis' => 'nullable|string|max:30',
            'catatan' => 'nullable|string|max:1000',
            'status' => 'required|in:aktif,nonaktif,verifikasi',
        ]);

        $supplier->update($data);

        return back()->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return back()->with('success', 'Supplier dihapus.');
    }
}
