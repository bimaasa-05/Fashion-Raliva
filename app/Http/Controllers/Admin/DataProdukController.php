<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class DataProdukController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $products = Product::with(['category', 'store', 'variants'])
            ->when($q, fn ($query) => $query->where('nama_produk', 'like', "%{$q}%"))
            ->orderByDesc('product_id')
            ->paginate(12);

        $categories = \App\Models\Category::where('status', 'aktif')->orderBy('nama_kategori')->get();

        return view('Admin.produk.index', compact('products', 'categories'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'tipe_produk' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string|max:2000',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
        ]);

        $storeId = AdminContext::assignedStoreIds()[0] ?? null;
        if (! $storeId) {
            return back()->with('error', 'Admin belum ditugaskan ke toko mana pun.');
        }

        Product::create(array_merge($data, [
            'store_id' => $storeId,
            'status' => Product::STATUS_PENDING,
            'alasan_penolakan' => 'Menunggu persetujuan Owner.',
        ]));

        return back()->with('success', 'Produk diajukan. Menunggu persetujuan Owner.');
    }
}
