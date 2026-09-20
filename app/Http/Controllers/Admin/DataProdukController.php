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
        $products = Product::with(['category', 'store', 'variants', 'images' => fn ($qq) => $qq->orderBy('urutan')])
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
            'ukuran_terpilih' => 'nullable|string|max:255',
            'warna' => 'nullable|array',
            'warna.*' => 'string|max:30',
            'warna_hex' => 'nullable|array',
            'warna_hex.*' => 'nullable|string|regex:/^#([0-9a-fA-F]{6})$/i',
            'varian_stok' => 'nullable|array',
            'varian_stok.*.ukuran' => 'required|string|max:255',
            'varian_stok.*.warna' => 'required|string|max:100',
            'varian_stok.*.stok' => 'nullable|integer|min:0',
            'varian_stok.*.stok_minimum' => 'nullable|integer|min:0',
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
            'alasan_penolakan' => 'Menunggu moderasi Super Admin.',
        ]);

        $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->first();
        if ($sa) {
            \App\Models\Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => \App\Models\Notification::TIPE_PROMO,
                'judul' => 'Produk Baru Diajukan',
                'pesan' => sprintf('Produk "%s" diajukan dan menunggu moderasi Super Admin.', $product->nama_produk),
                'url' => route('superadmin.moderasi-produk'),
            ]);
        }
        \App\Models\Notification::fireSelf(
            \App\Models\Notification::TIPE_PROMO,
            'Produk Diajukan',
            sprintf('Produk "%s" diajukan ke Super Admin untuk verifikasi.', $product->nama_produk),
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
                }
            }
        }

        // Handle variasi
        $ukuranList = $data['ukuran_terpilih'] ? explode(',', $data['ukuran_terpilih']) : ['All Size'];
        $warnaList = $data['warna'] ?? ['Hitam'];

        $warnaHexMap = collect($warnaList)->values()->mapWithKeys(function ($warna, $i) use ($data) {
            $name = trim($warna);
            $hex = trim((string) ($data['warna_hex'][$i] ?? ''));
            $resolved = ($hex && preg_match('/^#[0-9a-fA-F]{6}$/', $hex))
                ? $hex
                : (\App\Support\WarnaPalet::hex($name) ?? '');

            return [$name => $resolved];
        })->all();

        $perVarian = collect($data['varian_stok'] ?? [])->keyBy(function ($v) {
            return trim($v['ukuran']) . '|' . trim($v['warna']);
        });

        $warehouse = \App\Models\Warehouse::where('store_id', $storeId)->where('status', \App\Models\Warehouse::STATUS_AKTIF)->first();
        if (!$warehouse) {
            $warehouse = \App\Models\Warehouse::create([
                'store_id' => $storeId,
                'nama_gudang' => 'Gudang Utama',
                'status' => \App\Models\Warehouse::STATUS_AKTIF,
            ]);
        }

        foreach ($ukuranList as $uk) {
            foreach ($warnaList as $wr) {
                $key = trim($uk) . '|' . trim($wr);
                $detail = $perVarian->get($key);

                $variant = \App\Models\ProductVariant::create([
                    'product_id' => $product->product_id,
                    'sku' => strtoupper(substr($product->nama_produk, 0, 3)).'-'.str_pad($product->product_id, 4, '0').'-'.strtoupper(substr($uk,0,1)).substr($wr,0,1).rand(10,99),
                    'ukuran' => trim($uk),
                    'warna' => trim($wr),
                    'warna_hex' => $warnaHexMap[trim($wr)] ?? (\App\Support\WarnaPalet::hex(trim($wr)) ?? null),
                    'harga' => $data['harga_dasar'],
                    'status' => 'aktif',
                ]);

                if ($detail || $warehouse) {
                    $stok = (int) ($detail['stok'] ?? 0);
                    $stokMin = (int) ($detail['stok_minimum'] ?? 0);

                    if ($warehouse) {
                        \App\Models\WarehouseStock::updateOrCreate(
                            ['warehouse_id' => $warehouse->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                            ['jumlah_stok' => $stok, 'jumlah_direservasi' => 0, 'stok_minimum' => $stokMin]
                        );
                    }
                }
            }
        }

        return back()->with('success', 'Produk diajukan. Menunggu moderasi Super Admin.');
    }

    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
    {
        $assignedStores = AdminContext::assignedStoreIds();
        if (! in_array($product->store_id, $assignedStores, true)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah produk toko ini.');
        }

        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'tipe_produk' => 'sometimes|string|in:regular,preorder,made_to_order',
            'deskripsi' => 'nullable|string|max:2000',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
        ]);

        $resetStatus = ($product->status === Product::STATUS_DITOLAK);

        $product->update([
            'nama_produk' => $data['nama_produk'],
            'harga_dasar' => $data['harga_dasar'],
            'category_id' => $data['category_id'] ?? null,
            'tipe_produk' => $data['tipe_produk'] ?? $product->tipe_produk,
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $resetStatus ? Product::STATUS_PENDING : $product->status,
            'alasan_penolakan' => $resetStatus ? 'Diajukan ulang setelah revisi oleh Admin.' : $product->alasan_penolakan,
        ]);

        ActivityLogger::log('produk.update', Product::class, $product->product_id, [], $data, 'Admin memperbarui data produk');

        return back()->with('success', 'Data produk berhasil diperbarui.');
    }
}
