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

        return view('Admin.supplier.supplier', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:120',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Supplier::create($data);

        return back()->with('success', 'Supplier ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => 'required|string|max:120',
            'kontak' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:120',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
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
