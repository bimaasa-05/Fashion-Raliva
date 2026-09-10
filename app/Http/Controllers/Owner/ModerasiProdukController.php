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

    public function verifikasi(Product $product)
    {
        if ((int) $product->store_id !== (int) OwnerContext::firstStoreId()) abort(403);
        if ($product->status !== Product::STATUS_PENDING) {
            return back()->with('error', 'Hanya produk pending yang bisa diverifikasi.');
        }
        $product->update(['alasan_penolakan' => 'Terverifikasi Owner pada '.now()->translatedFormat('d M Y H:i').' — menunggu SuperAdmin.']);
        ActivityLogger::log('owner.product.verify', Product::class, $product->product_id, ['status' => 'pending'], ['status' => 'pending'], 'Owner verifikasi '.$product->nama_produk);
        $sa = User::whereHas('role', fn($q) => $q->where('nama_role', 'Super Admin'))->first();
        if ($sa) {
            Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Produk Terverifikasi Owner',
                'pesan' => "Produk {$product->nama_produk} sudah diverifikasi Owner, menunggu persetujuan SuperAdmin.",
                'url' => route('superadmin.moderasi-produk'),
            ]);
        }
        return back()->with('success', 'Produk diverifikasi, menunggu SuperAdmin.');
    }
}
