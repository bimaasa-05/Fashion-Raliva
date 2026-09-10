<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class ModerasiProdukController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $products = Product::with(['category', 'variants', 'images' => fn ($q) => $q->orderBy('urutan')])
            ->where('store_id', $storeId)
            ->whereIn('status', ['pending', 'ditolak', 'aktif'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'ditolak' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->paginate(12);

        $all = Product::where('store_id', $storeId)
            ->whereIn('status', ['pending', 'ditolak', 'aktif'])->get();
        $summary = [
            'total' => Product::where('store_id', $storeId)->where('status', '!=', 'arsip')->count(),
            'pending' => $all->where('status', 'pending')->count(),
            'aktif' => $all->where('status', 'aktif')->count(),
            'ditolak' => $all->where('status', 'ditolak')->count(),
        ];

        return view('Owner.moderasi-produk.index', compact('products', 'summary'));
    }

    public function setujui(Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);
        if ($product->status !== Product::STATUS_PENDING) {
            return back()->with('error', 'Hanya produk pending yang bisa disetujui.');
        }
        if ($product->owner_verified_at) {
            return back()->with('info', 'Produk ini sudah disetujui dan menunggu SuperAdmin.');
        }
        $lama = $product->only(['status', 'owner_verified_at']);
        $product->update(['owner_verified_at' => now()]);
        ActivityLogger::log('owner.product.approve', Product::class, $product->product_id, $lama, ['owner_verified_at' => $product->owner_verified_at], 'Owner menyetujui '.$product->nama_produk.' — menunggu SuperAdmin');
        $sa = User::whereHas('role', fn($q) => $q->where('nama_role', 'Super Admin'))->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Produk Disetujui Owner',
                'pesan' => "Produk {$product->nama_produk} sudah disetujui Owner, menunggu persetujuan SuperAdmin.",
                'url' => route('superadmin.moderasi-produk'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Disetujui', sprintf('Produk "%s" disetujui, menunggu SuperAdmin.', $product->nama_produk), route('owner.moderasi-produk'));
        return back()->with('success', 'Produk disetujui, menunggu SuperAdmin.');
    }

    public function tolak(Request $request, Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);
        if ($product->status !== Product::STATUS_PENDING) {
            return back()->with('error', 'Hanya produk pending yang bisa ditolak.');
        }
        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);
        $lama = $product->only(['status', 'alasan_penolakan']);
        $product->update([
            'status' => Product::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan'],
        ]);
        ActivityLogger::log('owner.product.reject', Product::class, $product->product_id, $lama, ['status' => Product::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']], 'Owner menolak '.$product->nama_produk.': '.$data['alasan']);
        $sa = User::whereHas('role', fn($q) => $q->where('nama_role', 'Super Admin'))->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Produk Ditolak Owner',
                'pesan' => sprintf('Produk "%s" ditolak Owner. Alasan: %s', $product->nama_produk, $data['alasan']),
                'url' => route('superadmin.moderasi-produk'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Ditolak', sprintf('Produk "%s" ditolak.', $product->nama_produk), route('owner.moderasi-produk'));
        return back()->with('success', 'Produk ditolak. Admin dapat memperbaiki dan mengajukan ulang.');
    }
}
