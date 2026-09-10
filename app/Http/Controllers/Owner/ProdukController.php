<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Category;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Support\ActivityLogger;
use App\Support\OwnerContext;
use App\Support\SlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $query = Product::with(['category', 'variants', 'images'])
            ->where('store_id', $storeId);

        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($qq) use ($q) {
                $qq->where('nama_produk', 'like', "%{$q}%")
                   ->orWhereHas('variants', fn($v) => $v->where('sku', 'like', "%{$q}%"));
            });
        }
        if ($kat = $request->input('kategori')) {
            $katLabel = \Illuminate\Support\Str::title($kat);
            $query->whereHas('category', fn($q) => $q->where('nama_kategori', $katLabel));
        }
        if ($status = $request->input('status-produk')) {
            if (in_array($status, ['aktif', 'nonaktif', 'pending', 'ditolak', 'draft', 'arsip'])) {
                $query->where('status', $status);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::where('status', 'aktif')->orderBy('nama_kategori')->pluck('nama_kategori');
        $categoryOptions = Category::where('status', 'aktif')->orderBy('nama_kategori')->pluck('nama_kategori', 'category_id');

        // terjual per produk (dari order item via variant)
        $ids = $products->pluck('product_id')->all();
        $sold = OrderItem::query()
            ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as total'))
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->whereIn('product_variants.product_id', $ids)
            ->groupBy('product_variants.product_id')
            ->pluck('total', 'product_variants.product_id');
        $products->getCollection()->transform(function ($p) use ($sold) {
            $p->terjual = (int) ($sold->get($p->product_id) ?? 0);
            return $p;
        });

        $slotAgg = \App\Models\StoreSlotSubscription::where('store_id', $storeId)
            ->where('status', 'aktif')
            ->selectRaw('COALESCE(SUM(jumlah_slot),0) as total, COALESCE(SUM(slot_terpakai),0) as used')
            ->first();
        $totalSlot = (int) ($slotAgg->total ?? 0);
        $usedSlot = (int) ($slotAgg->used ?? 0);

        $counts = [
            'total' => Product::where('store_id', $storeId)->count(),
            'aktif' => Product::where('store_id', $storeId)->where('status', 'aktif')->count(),
            'nonaktif' => Product::where('store_id', $storeId)->where('status', 'nonaktif')->count(),
            'pending' => Product::where('store_id', $storeId)->where('status', 'pending')->count(),
            'varian' => \App\Models\ProductVariant::whereHas('product', fn($q) => $q->where('store_id', $storeId))->count(),
        ];

        return view('Owner.produk.index', compact('products', 'counts', 'totalSlot', 'usedSlot', 'categories', 'categoryOptions'));
    }

    public function update(Request $request, Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);
        if (! in_array($product->status, [Product::STATUS_PENDING, Product::STATUS_DITOLAK, Product::STATUS_DRAFT], true)) {
            return back()->with('error', 'Hanya produk pending, ditolak, atau draft yang bisa diubah.');
        }

        $data = $request->validate([
            'nama_produk' => 'sometimes|required|string|max:255',
            'harga_dasar' => 'sometimes|required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'tipe_produk' => 'sometimes|string|in:regular,preorder,made_to_order',
            'deskripsi' => 'nullable|string|max:2000',
            'foto_produk' => 'nullable|array|max:8',
            'foto_produk.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_dasar.required' => 'Harga dasar wajib diisi.',
            'harga_dasar.numeric' => 'Harga harus berupa angka.',
        ]);

        $wasRejected = $product->status === Product::STATUS_DITOLAK;
        $lama = $product->only(['nama_produk', 'harga_dasar', 'category_id', 'tipe_produk', 'deskripsi', 'status']);

        $product->update([
            'nama_produk' => $data['nama_produk'] ?? $product->nama_produk,
            'harga_dasar' => $data['harga_dasar'] ?? $product->harga_dasar,
            'category_id' => array_key_exists('category_id', $data) ? $data['category_id'] : $product->category_id,
            'tipe_produk' => $data['tipe_produk'] ?? $product->tipe_produk,
            'deskripsi' => array_key_exists('deskripsi', $data) ? $data['deskripsi'] : $product->deskripsi,
        ]);

        if ($request->hasFile('foto_produk')) {
            $next = (int) ($product->images()->max('urutan') ?? -1) + 1;
            foreach ($request->file('foto_produk') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'file_gambar' => $path,
                        'urutan' => $next++,
                    ]);
                }
            }
        }

        if ($wasRejected) {
            $product->update(['status' => Product::STATUS_PENDING, 'alasan_penolakan' => null, 'owner_verified_at' => null]);
            $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->first();
            if ($sa) {
                Notification::create([
                    'user_id' => $sa->user_id,
                    'aktor_id' => ActivityLogger::resolveActorId(),
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Produk Diperbaiki Owner',
                    'pesan' => sprintf('Produk "%s" diperbaiki Owner dan menunggu review.', $product->nama_produk),
                    'url' => route('superadmin.moderasi-produk'),
                ]);
            }
        }

        ActivityLogger::log('owner.product.update', Product::class, $product->product_id, $lama, $product->only(['nama_produk', 'harga_dasar', 'category_id', 'tipe_produk', 'deskripsi', 'status']), 'Owner memperbarui produk '.$product->nama_produk);
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Diperbarui', sprintf('Produk "%s" berhasil diperbarui.', $product->nama_produk), route('owner.produk'));

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);

        $variantIds = $product->variants()->pluck('product_variant_id')->all();
        $hasOrders = ! empty($variantIds) && OrderItem::whereIn('product_variant_id', $variantIds)->exists();
        $hasReviews = Review::where('product_id', $product->product_id)->exists();
        $hasAds = AdSlot::where('product_id', $product->product_id)->exists();

        if ($hasOrders || $hasReviews || $hasAds) {
            return back()->with('error', 'Produk tidak dapat dihapus karena memiliki riwayat (pesanan/ulasan/slot iklan). Nonaktifkan saja bila perlu.');
        }

        $lama = $product->only(['nama_produk', 'status']);
        $paths = $product->images()->pluck('file_gambar')->all();

        try {
            DB::transaction(function () use ($product) {
                $product->images()->delete();
                $product->variants()->delete();
                $product->delete();
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Produk tidak dapat dihapus karena terkait data lain. Nonaktifkan saja bila perlu.');
        }

        foreach ($paths as $p) {
            if ($p) Storage::disk('public')->delete($p);
        }

        ActivityLogger::log('owner.product.delete', Product::class, $product->product_id, $lama, [], 'Owner menghapus produk '.($lama['nama_produk'] ?? ''));
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Dihapus', sprintf('Produk "%s" telah dihapus.', $lama['nama_produk'] ?? ''), route('owner.produk'));

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function status(Request $request, Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);

        $allowed = [
            Product::STATUS_AKTIF => [Product::STATUS_AKTIF, Product::STATUS_NONAKTIF],
            Product::STATUS_NONAKTIF => [Product::STATUS_NONAKTIF, Product::STATUS_AKTIF],
            Product::STATUS_DRAFT => [Product::STATUS_DRAFT, Product::STATUS_PENDING],
        ];

        $data = $request->validate([
            'status' => 'required|string',
        ]);

        $from = $product->status;
        $to = $data['status'];

        if (! isset($allowed[$from]) || ! in_array($to, $allowed[$from], true)) {
            return back()->with('error', 'Perubahan status tidak diizinkan.');
        }

        if ($to === $from) {
            return back();
        }

        if ($to === Product::STATUS_AKTIF) {
            $storeId = (int) $product->store_id;
            if (! SlotService::canAdd($storeId)) {
                $total = SlotService::totalQuota($storeId);
                $used = SlotService::usedSlots($storeId);

                return back()->with('error', sprintf('Kuota slot produk penuh (%d/%d). Tambah slot terlebih dahulu.', $used, $total));
            }
        }

        $lama = $product->only(['status']);
        $product->update(['status' => $to]);

        ActivityLogger::log('owner.product.status', Product::class, $product->product_id, $lama, ['status' => $to], sprintf('Owner mengubah status produk %s menjadi %s.', $product->nama_produk, $to));

        if ($from === Product::STATUS_DRAFT && $to === Product::STATUS_PENDING) {
            $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->first();
            if ($sa) {
                Notification::create([
                    'user_id' => $sa->user_id,
                    'aktor_id' => ActivityLogger::resolveActorId(),
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Produk Diajukan Review',
                    'pesan' => sprintf('Produk "%s" diajukan Owner dan menunggu moderasi.', $product->nama_produk),
                    'url' => route('superadmin.moderasi-produk'),
                ]);
            }
            Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Diajukan', sprintf('Produk "%s" diajukan, menunggu moderasi.', $product->nama_produk), route('owner.produk'));
        } else {
            $label = $to === Product::STATUS_AKTIF ? 'Diaktifkan' : 'Dinonaktifkan';
            Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk ' . $label, sprintf('Produk "%s" %s.', $product->nama_produk, strtolower($label)), route('owner.produk'));
        }

        return back()->with('success', sprintf('Status produk diubah menjadi %s.', $to));
    }
}
