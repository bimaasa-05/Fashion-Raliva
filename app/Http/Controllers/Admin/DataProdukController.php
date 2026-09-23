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

        return view('Admin.produk.index', compact('products', 'categories', 'stats', 'ukuranOptions', 'tokoKategori'));
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
            'warna' => 'required|array|min:1',
            'warna.*' => 'string|max:30',
            'warna_hex' => 'nullable|array',
            'warna_hex.*' => 'nullable|string|regex:/^#([0-9a-fA-F]{6})$/i',
            'varian_stok' => 'required|array|min:1',
            'varian_stok.*.ukuran' => 'required|string|max:255',
            'varian_stok.*.warna' => 'required|string|max:100',
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
            'warna.required' => 'Pilih minimal 1 warna.',
            'warna.min' => 'Pilih minimal 1 warna.',
            'varian_stok.required' => 'Isi stok untuk setiap varian.',
            'varian_stok.min' => 'Isi stok untuk setiap varian.',
            'varian_stok.*.stok.required' => 'Stok tiap varian wajib diisi.',
            'varian_stok.*.stok.min' => 'Stok tiap varian minimal 1.',
            'varian_stok.*.stok_minimum.required' => 'Ambang menipis tiap varian wajib diisi.',
        ]);

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
            'warna' => 'nullable|array',
            'warna.*' => 'string|max:30',
            'warna_hex' => 'nullable|array',
            'warna_hex.*' => 'nullable|string|regex:/^#([0-9a-fA-F]{6})$/i',
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

        $resetStatus = ($product->status === Product::STATUS_DITOLAK);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $product, $data, $resetStatus) {
            $product->update([
                'nama_produk' => $data['nama_produk'],
                'harga_dasar' => $data['harga_dasar'],
                'category_id' => $data['category_id'] ?? null,
                'tipe_produk' => $data['tipe_produk'] ?? $product->tipe_produk,
                'deskripsi' => $data['deskripsi'] ?? null,
                'status' => $resetStatus ? Product::STATUS_PENDING : $product->status,
                'alasan_penolakan' => $resetStatus ? 'Diajukan ulang setelah revisi oleh Admin.' : $product->alasan_penolakan,
            ]);

            // Hapus foto terpilih (milik produk ini saja)
            $hapusIds = collect($data['hapus_foto_ids'] ?? [])->map(fn ($v) => (int) $v)->all();
            if ($hapusIds) {
                $fotos = \App\Models\ProductImage::where('product_id', $product->product_id)
                    ->whereIn('product_image_id', $hapusIds)->get();
                foreach ($fotos as $f) {
                    try { \Illuminate\Support\Facades\Storage::disk('public')->delete($f->file_gambar); } catch (\Throwable $e) {}
                    $f->delete();
                }
            }

            // Tambah foto baru (total maksimal 5)
            $sisaSlot = 5 - \App\Models\ProductImage::where('product_id', $product->product_id)->count();
            if ($request->hasFile('foto_produk') && $sisaSlot > 0) {
                $urutan = (int) (\App\Models\ProductImage::where('product_id', $product->product_id)->max('urutan') ?? -1) + 1;
                foreach ($request->file('foto_produk') as $file) {
                    if ($sisaSlot <= 0) break;
                    if ($file && $file->isValid()) {
                        $path = $file->store('products', 'public');
                        \App\Models\ProductImage::create([
                            'product_id' => $product->product_id,
                            'file_gambar' => $path,
                            'urutan' => $urutan++,
                        ]);
                        $sisaSlot--;
                    }
                }
            }

            // Sinkron varian + stok (tambah-bukan-hapus; varian ber-order tidak dihapus)
            $ukuranList = isset($data['ukuran_terpilih']) && $data['ukuran_terpilih'] !== ''
                ? array_values(array_filter(array_map('trim', explode(',', $data['ukuran_terpilih']))))
                : [];
            $warnaList = array_values(array_filter(array_map('trim', $data['warna'] ?? [])));
            if ($ukuranList && $warnaList) {
                $warnaHexMap = collect($warnaList)->values()->mapWithKeys(function ($warna, $i) use ($data) {
                    $hex = trim((string) ($data['warna_hex'][$i] ?? ''));
                    return [$warna => ($hex && preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) ? $hex : (\App\Support\WarnaPalet::hex($warna) ?? '')];
                })->all();

                $perVarian = collect($data['varian_stok'] ?? [])->keyBy(fn ($v) => trim($v['ukuran']).'|'.trim($v['warna']));
                $warehouse = \App\Models\Warehouse::where('store_id', $product->store_id)
                    ->where('status', \App\Models\Warehouse::STATUS_AKTIF)->first();
                $existing = \App\Models\ProductVariant::where('product_id', $product->product_id)->get()
                    ->keyBy(fn ($v) => trim($v->ukuran).'|'.trim($v->warna));

                foreach ($ukuranList as $uk) {
                    foreach ($warnaList as $wr) {
                        $key = trim($uk).'|'.trim($wr);
                        $detail = $perVarian->get($key);
                        $variant = $existing->get($key);
                        if (! $variant) {
                            $variant = \App\Models\ProductVariant::create([
                                'product_id' => $product->product_id,
                                'sku' => strtoupper(substr($product->nama_produk, 0, 3)).'-'.str_pad($product->product_id, 4, '0').'-'.strtoupper(substr($uk, 0, 1)).substr($wr, 0, 1).rand(10, 99),
                                'ukuran' => trim($uk),
                                'warna' => trim($wr),
                                'warna_hex' => $warnaHexMap[trim($wr)] ?? (\App\Support\WarnaPalet::hex(trim($wr)) ?? null),
                                'harga' => $data['harga_dasar'],
                                'status' => 'aktif',
                            ]);
                            $existing[$key] = $variant;
                        } else {
                            $variant->update([
                                'warna_hex' => $warnaHexMap[trim($wr)] ?? $variant->warna_hex,
                                'harga' => $data['harga_dasar'],
                            ]);
                        }
                        if ($warehouse) {
                            \App\Models\WarehouseStock::updateOrCreate(
                                ['warehouse_id' => $warehouse->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                                ['jumlah_stok' => (int) ($detail['stok'] ?? 0), 'jumlah_direservasi' => 0, 'stok_minimum' => (int) ($detail['stok_minimum'] ?? 0)]
                            );
                        }
                    }
                }
            }
        });

        ActivityLogger::log('produk.update', Product::class, $product->product_id, [], $data, 'Admin memperbarui data produk (teks, foto, varian, stok)');

        \App\Models\Notification::fireSelf(\App\Models\Notification::TIPE_SISTEM, 'Produk Diperbarui', sprintf('Produk "%s" berhasil diperbarui.', $product->nama_produk), route('admin.produk'));

        return back()->with('success', 'Data produk berhasil diperbarui (teks, foto, varian, stok).');
    }
}
