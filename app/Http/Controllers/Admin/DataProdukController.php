<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ActivityLogger;
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

        $stats = [
            'total' => Product::count(),
            'aktif' => Product::where('status', 'aktif')->count(),
            'pending' => Product::where('status', 'pending')->count(),
            'ditolak' => Product::where('status', 'ditolak')->count(),
        ];

        return view('Admin.produk.index', compact('products', 'categories', 'stats'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'tipe_produk' => 'sometimes|string|in:regular,preorder,made_to_order',
            'deskripsi' => 'nullable|string|max:2000',
            'foto_produk' => 'nullable|array|max:8',
            'foto_produk.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok_awal' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'ukuran_terpilih' => 'nullable|string|max:50',
            'warna' => 'nullable|array',
            'warna.*' => 'string|max:30',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
        ]);

        $storeId = AdminContext::assignedStoreIds()[0] ?? null;
        if (! $storeId) {
            return back()->with('error', 'Admin belum ditugaskan ke toko mana pun.');
        }

        $product = Product::create([
            'store_id' => $storeId,
            'category_id' => $data['category_id'] ?? null,
            'nama_produk' => $data['nama_produk'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga_dasar' => $data['harga_dasar'],
            'tipe_produk' => $data['tipe_produk'] ?? Product::TIPE_REGULAR,
            'status' => Product::STATUS_PENDING,
            'alasan_penolakan' => 'Menunggu persetujuan Owner.',
        ]);

        $store = \App\Models\Store::find($storeId);
        if ($store && $store->owner_id) {
            \App\Models\Notification::create([
                'user_id' => $store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => \App\Models\Notification::TIPE_PROMO,
                'judul' => 'Produk Baru Diajukan',
                'pesan' => sprintf('Produk "%s" diajukan dan menunggu verifikasi Owner.', $product->nama_produk),
                'url' => route('owner.produk'),
            ]);
        }
        \App\Models\Notification::fireSelf(
            \App\Models\Notification::TIPE_PROMO,
            'Produk Diajukan',
            sprintf('Produk "%s" diajukan ke Owner untuk verifikasi.', $product->nama_produk),
            route('admin.produk')
        );

        // Handle foto upload
        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $idx => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('products', 'public');
                    \App\Models\ProductImage::create([
                        'product_id' => $product->product_id,
                        'file_gambar' => $path,
                        'urutan' => $idx,
                    ]);
                    if ($idx === 0) $product->update(['gambar_utama' => $path]);
                }
            }
        }

        // Handle variasi
        $ukuranList = $data['ukuran_terpilih'] ? explode(',', $data['ukuran_terpilih']) : ['All Size'];
        $warnaList = $data['warna'] ?? ['Hitam'];
        $stokAwal = (int) ($data['stok_awal'] ?? 50);
        $stokMin = (int) ($data['stok_minimum'] ?? 10);
        foreach ($ukuranList as $uk) {
            foreach ($warnaList as $wr) {
                \App\Models\ProductVariant::create([
                    'product_id' => $product->product_id,
                    'sku' => strtoupper(substr($product->nama_produk, 0, 3)).'-'.str_pad($product->product_id, 4, '0').'-'.strtoupper(substr($uk,0,1)).substr($wr,0,1).rand(10,99),
                    'ukuran' => trim($uk),
                    'warna' => trim($wr),
                    'harga' => $data['harga_dasar'],
                    'stok' => (int) ($stokAwal / max(1, count($ukuranList)*count($warnaList))),
                    'stok_minimum' => $stokMin,
                    'status' => 'aktif',
                ]);
            }
        }

        return back()->with('success', 'Produk diajukan. Menunggu persetujuan Owner.');
    }
}
