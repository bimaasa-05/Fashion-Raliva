<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ModerasiProdukController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', Product::STATUS_PENDING);

        $stats = [
            Product::STATUS_PENDING => Product::where('status', Product::STATUS_PENDING)->count(),
            Product::STATUS_AKTIF => Product::where('status', Product::STATUS_AKTIF)->count(),
            Product::STATUS_DITOLAK => Product::where('status', Product::STATUS_DITOLAK)->count(),
        ];

        $products = Product::query()
            ->with(['store:owner_id,store_id,nama_toko', 'category', 'images', 'variants'])
            ->whereIn('status', [Product::STATUS_PENDING, Product::STATUS_AKTIF, Product::STATUS_DITOLAK])
            ->when(
                in_array($status, [Product::STATUS_PENDING, Product::STATUS_AKTIF, Product::STATUS_DITOLAK], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'ditolak' THEN 1 ELSE 2 END")
            ->orderByDesc('updated_at')
            ->get();

        return view('SuperAdmin.moderasi-produk.index', [
            'products' => $products,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }

    public function setujui(Request $request, Product $produk)
    {
        if ($produk->status !== Product::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya produk berstatus pending yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $produk->only(['status', 'alasan_penolakan']);

        $produk->update([
            'status' => Product::STATUS_AKTIF,
            'alasan_penolakan' => null,
        ]);

        ActivityLogger::log(
            'product.approve',
            Product::class,
            $produk->product_id,
            $lama,
            ['status' => Product::STATUS_AKTIF],
            sprintf('Menyetujui produk "%s" dari toko %s.', $produk->nama_produk, $produk->store->nama_toko ?? '-')
        );

        if ($produk->store) {
            Notification::create([
                'user_id' => $produk->store->owner_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Produk Disetujui',
                'pesan' => sprintf('Produk "%s" telah disetujui moderasi dan kini dapat tampil di Raliva.', $produk->nama_produk),
            ]);
        }

        return back()->with('toast', [
            'message' => sprintf('Produk %s disetujui dan kini dapat tampil.', $produk->nama_produk),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Product $produk)
    {
        if ($produk->status !== Product::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya produk berstatus pending yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $produk->only(['status', 'alasan_penolakan']);

        $produk->update([
            'status' => Product::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'product.reject',
            Product::class,
            $produk->product_id,
            $lama,
            ['status' => Product::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak produk "%s" dengan alasan: %s', $produk->nama_produk, $data['alasan'])
        );

        if ($produk->store) {
            Notification::create([
                'user_id' => $produk->store->owner_id,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Produk Ditolak Moderasi',
                'pesan' => sprintf('Produk "%s" ditolak moderasi. Alasan: %s. Silakan perbaiki lalu kirim ulang.', $produk->nama_produk, $data['alasan']),
            ]);
        }

        return back()->with('toast', [
            'message' => sprintf('Produk %s ditolak. Alasan dikirim ke pemilik toko.', $produk->nama_produk),
            'icon' => 'block',
        ]);
    }
}
