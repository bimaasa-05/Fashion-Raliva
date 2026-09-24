<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use App\Support\WarnaPalet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataProdukController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $products = Product::with(['category', 'store', 'variants.warehouseStocks', 'images' => fn ($qq) => $qq->orderBy('urutan')])
            ->when($q, fn ($query) => $query->where('nama_produk', 'like', "%{$q}%"))
            ->orderByDesc('created_at')
            ->orderByDesc('product_id')
            ->paginate(12);

        $categories = \App\Models\Category::where('status', 'aktif')->orderBy('nama_kategori')->get();

        // Ukuran berdasarkan kategori toko Admin (fallback hardcode lama)
        $fallbackUkuran = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'];
        $ukuranOptions = $fallbackUkuran;
        $tokoKategori = null;
        $tokoPertama = \App\Models\Store::whereIn('store_id', AdminContext::assignedStoreIds())->first(['store_id', 'kategori']);
        if ($tokoPertama?->kategori) {
            $tokoKategori = $tokoPertama->kategori;
            $sizes = \App\Models\StoreCategorySize::whereHas('storeCategory', fn ($q) => $q->where('nama_kategori', $tokoKategori))
                ->orderBy('urutan')->pluck('ukuran_label')->all();
            if ($sizes) $ukuranOptions = $sizes;
        }

        $stats = [
            'total' => Product::count(),
            'aktif' => Product::where('status', 'aktif')->count(),
            'pending' => Product::where('status', 'pending')->count(),
            'ditolak' => Product::where('status', 'ditolak')->count(),
        ];
        $pendingUpdateIds = \App\Models\ProductUpdateRequest::where('status', \App\Models\ProductUpdateRequest::STATUS_PENDING)
            ->pluck('product_id')
            ->all();

        return view('Admin.produk.index', compact('products', 'categories', 'stats', 'ukuranOptions', 'tokoKategori', 'pendingUpdateIds'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->merge(['harga_dasar' => str_replace('.', '', (string) $request->input('harga_dasar', ''))]);
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:1|max:999999999999',
            'category_id' => 'required|exists:categories,category_id',
            'tipe_produk' => 'required|string|in:regular,preorder,made_to_order',
            'deskripsi' => 'required|string|min:10|max:2000',
            'foto_produk' => 'required|array|min:1|max:5',
            'foto_produk.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok_awal' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'ukuran_terpilih' => 'required|string|max:1000',
            'varian_stok' => 'required|array|min:1',
            'varian_stok.*.ukuran' => 'required|string|max:255',
            'varian_stok.*.warna' => 'nullable|string|max:100',
            'varian_stok.*.stok' => 'required|integer|min:1',
            'varian_stok.*.stok_minimum' => 'required|integer|min:0',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
            'harga_dasar.min' => 'Harga minimal Rp 1.',
            'harga_dasar.max' => 'Harga maksimal Rp 999.999.999.999.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'tipe_produk.required' => 'Tipe produk wajib dipilih.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'foto_produk.required' => 'Minimal 1 foto produk wajib diunggah.',
            'foto_produk.min' => 'Minimal 1 foto produk wajib diunggah.',
            'ukuran_terpilih.required' => 'Pilih minimal 1 ukuran.',
            'varian_stok.required' => 'Isi stok untuk setiap varian.',
            'varian_stok.min' => 'Isi stok untuk setiap varian.',
            'varian_stok.*.stok.required' => 'Stok tiap varian wajib diisi.',
            'varian_stok.*.stok.min' => 'Stok tiap varian minimal 1.',
            'varian_stok.*.stok_minimum.required' => 'Ambang menipis tiap varian wajib diisi.',
        ]);

        $warnaInput = $request->input('warna', []);
        $warnaHexInput = $request->input('warna_hex', []);
        $warna = WarnaPalet::normalizeOptionalSubmissionOrFail($warnaInput, $warnaHexInput);
        $data['warna'] = $warna['names'];
        $data['warna_hex'] = $warna['hexes'];

        $storeId = AdminContext::assignedStoreIds()[0] ?? null;
        if (! $storeId) {
            return back()->with('error', 'Admin belum ditugaskan ke toko mana pun.');
        }
        if (! \App\Support\SlotService::canAdd((int) $storeId)) {
            $total = \App\Support\SlotService::totalQuota((int) $storeId);
            $used = \App\Support\SlotService::usedSlots((int) $storeId);

            return back()->with('error', sprintf('Kuota slot produk penuh (%d/%d). Ajukan pembelian slot di menu Beli Slot terlebih dahulu.', $used, $total));
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
        $warnaList = ($data['warna'] ?? []) !== [] ? $data['warna'] : [null];

        $warnaHexMap = collect($warnaList)->values()->mapWithKeys(function ($warna, $i) use ($data) {
            if ($warna === null) {
                return [];
            }
            $name = trim((string) $warna);
            $hex = trim((string) ($data['warna_hex'][$i] ?? ''));
            $resolved = \App\Support\WarnaPalet::resolve($hex, $name) ?? '';

            return [$name => $resolved];
        })->all();

        $perVarian = collect($data['varian_stok'] ?? [])->keyBy(function ($v) {
            return trim((string) ($v['ukuran'] ?? '')) . '|' . trim((string) ($v['warna'] ?? ''));
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
                $color = $wr === null ? null : trim((string) $wr);
                $key = trim((string) $uk) . '|' . trim((string) $color);
                $detail = $perVarian->get($key);

                $variant = \App\Models\ProductVariant::create([
                    'product_id' => $product->product_id,
                    'sku' => strtoupper(substr($product->nama_produk, 0, 3)).'-'.str_pad($product->product_id, 4, '0').'-'.strtoupper(substr($uk,0,1)).($color === null ? '' : substr($color,0,1)).rand(10,99),
                    'ukuran' => trim($uk),
                    'warna' => $color,
                    'warna_hex' => $color === null ? null : ($warnaHexMap[$color] ?? \App\Support\WarnaPalet::resolve(null, $color)),
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

        $request->merge(['harga_dasar' => str_replace('.', '', (string) $request->input('harga_dasar', ''))]);

        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0|max:999999999999',
            'category_id' => 'nullable|exists:categories,category_id',
            'tipe_produk' => 'sometimes|string|in:regular,preorder,made_to_order',
            'deskripsi' => 'nullable|string|max:2000',
            'foto_produk' => 'nullable|array|max:5',
            'foto_produk.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto_ids' => 'nullable|array',
            'hapus_foto_ids.*' => 'integer|exists:product_images,product_image_id',
            'ukuran_terpilih' => 'nullable|string|max:1000',
            'varian_stok' => 'nullable|array',
            'varian_stok.*.variant_id' => 'nullable|integer|exists:product_variants,product_variant_id',
            'varian_stok.*.ukuran' => 'required|string|max:255',
            'varian_stok.*.warna' => 'required|string|max:100',
            'varian_stok.*.stok' => 'nullable|integer|min:0',
            'varian_stok.*.stok_minimum' => 'nullable|integer|min:0',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
            'harga_dasar.max' => 'Harga maksimal Rp 999.999.999.999.',
        ]);

        if ($request->has('warna') || $request->has('warna_hex')) {
            $warna = WarnaPalet::normalizeOptionalSubmissionOrFail($request->input('warna'), $request->input('warna_hex'));
            $data['warna'] = $warna['names'];
            $data['warna_hex'] = $warna['hexes'];
        }

        if (\App\Models\ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', \App\Models\ProductUpdateRequest::STATUS_PENDING)
            ->exists()
        ) {
            return back()->with('error', 'Produk ini sudah mempunyai pengajuan perubahan yang menunggu keputusan Super Admin.');
        }

        $existingImages = \App\Models\ProductImage::where('product_id', $product->product_id)
            ->orderBy('urutan')
            ->get();
        $removeIds = $existingImages->pluck('product_image_id')
            ->intersect(collect($data['hapus_foto_ids'] ?? [])->map(fn ($v) => (int) $v))
            ->values()
            ->all();
        $newFiles = collect($request->file('foto_produk', []))->filter(fn ($file) => $file && $file->isValid())->values();
        if (($existingImages->count() - count($removeIds) + $newFiles->count()) > 5) {
            return back()->with('error', 'Maksimal total 5 foto. Hapus foto lama dulu sebelum menambah foto baru.');
        }

        $product->load(['category', 'images' => fn ($query) => $query->orderBy('urutan'), 'variants.warehouseStocks']);
        $before = [
            'product' => array_merge(
                $product->only(['nama_produk', 'harga_dasar', 'category_id', 'tipe_produk', 'deskripsi', 'status']),
                ['kategori' => $product->category?->nama_kategori]
            ),
            'images' => $existingImages->map(fn ($image) => $image->only(['product_image_id', 'file_gambar', 'urutan']))->all(),
            'variants' => $product->variants->map(fn ($variant) => [
                'product_variant_id' => $variant->product_variant_id,
                'ukuran' => $variant->ukuran,
                'warna' => $variant->warna,
                'warna_hex' => $variant->warna_hex,
                'harga' => $variant->harga,
                'stok' => (int) $variant->warehouseStocks->sum('jumlah_stok'),
                'stok_minimum' => (int) ($variant->warehouseStocks->min('stok_minimum') ?? 0),
            ])->all(),
        ];

        $stagingToken = (string) Str::uuid();
        $stagedPaths = [];
        foreach ($newFiles as $file) {
            $stagedPaths[] = $file->storeAs(
                'pending-product-updates/'.$stagingToken,
                Str::uuid().'.'.$file->extension(),
                'public'
            );
        }

        $after = [
            'nama_produk' => $data['nama_produk'],
            'harga_dasar' => $data['harga_dasar'],
            'category_id' => $data['category_id'] ?? null,
            'tipe_produk' => $data['tipe_produk'] ?? $product->tipe_produk,
            'deskripsi' => $data['deskripsi'] ?? null,
            'ukuran_terpilih' => $data['ukuran_terpilih'] ?? '',
            'warna' => $data['warna'] ?? [],
            'warna_hex' => $data['warna_hex'] ?? [],
            'varian_stok' => $data['varian_stok'] ?? [],
            'hapus_foto_ids' => $removeIds,
            'staged_images' => $stagedPaths,
        ];

        $permintaan = \App\Models\ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $product->store_id,
            'requested_by' => ActivityLogger::resolveActorId(),
            'status' => \App\Models\ProductUpdateRequest::STATUS_PENDING,
            'before_snapshot' => $before,
            'after_payload' => $after,
            'remove_image_ids' => $removeIds,
            'staged_images' => $stagedPaths,
        ]);

        ActivityLogger::log(
            'produk.update.propose',
            Product::class,
            $product->product_id,
            ['status' => 'aktif'],
            ['status' => 'pengajuan_pending', 'product_update_request_id' => $permintaan->product_update_request_id],
            sprintf('Admin mengajukan perubahan produk "%s" untuk keputusan Super Admin.', $product->nama_produk)
        );

        $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->first();
        if ($sa) {
            \App\Models\Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => \App\Models\Notification::TIPE_SISTEM,
                'judul' => 'Pengajuan Perubahan Produk',
                'pesan' => sprintf('Perubahan produk "%s" menunggu keputusan Super Admin.', $product->nama_produk),
                'url' => route('superadmin.perubahan-produk'),
            ]);
        }
        if ($product->store?->owner_id) {
            \App\Models\Notification::create([
                'user_id' => $product->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => \App\Models\Notification::TIPE_SISTEM,
                'judul' => 'Perubahan Produk Diajukan',
                'pesan' => sprintf('Admin mengajukan perubahan produk "%s". Berlaku setelah disetujui Super Admin.', $product->nama_produk),
                'url' => route('owner.produk'),
            ]);
        }
        \App\Models\Notification::fireSelf(
            \App\Models\Notification::TIPE_SISTEM,
            'Perubahan Produk Diajukan',
            sprintf('Perubahan produk "%s" menunggu keputusan Super Admin.', $product->nama_produk),
            route('admin.produk')
        );

        return back()->with('success', 'Perubahan produk diajukan. Berlaku setelah disetujui Super Admin.');
    }
}
