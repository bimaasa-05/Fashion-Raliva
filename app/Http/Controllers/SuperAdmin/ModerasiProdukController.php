<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Support\ActivityLogger;
use App\Support\SlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModerasiProdukController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', Product::STATUS_PENDING);

        $stats = [
            Product::STATUS_PENDING => Product::where('status', Product::STATUS_PENDING)->count(),
            Product::STATUS_DITOLAK => Product::where('status', Product::STATUS_DITOLAK)->count(),
        ];

        $products = Product::query()
            ->with(['store:owner_id,store_id,nama_toko', 'category', 'images', 'variants'])
            ->whereIn('status', [Product::STATUS_PENDING, Product::STATUS_DITOLAK])
            ->when(
                in_array($status, [Product::STATUS_PENDING, Product::STATUS_DITOLAK], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'ditolak' THEN 1 ELSE 2 END")
            ->orderByDesc('updated_at')
            ->get();

        $slotCache = [];
        $products->each(function (Product $produk) use (&$slotCache) {
            $storeId = $produk->store_id;

            if ($storeId === null) {
                $produk->slot_total = 0;
                $produk->slot_used = 0;
                $produk->slot_available = 0;
                $produk->slot_full = false;

                return;
            }

            if (! array_key_exists($storeId, $slotCache)) {
                $slotCache[$storeId] = [
                    'total' => SlotService::totalQuota($storeId),
                    'used' => SlotService::usedSlots($storeId),
                ];
            }

            $total = $slotCache[$storeId]['total'];
            $used = $slotCache[$storeId]['used'];

            $produk->slot_total = $total;
            $produk->slot_used = $used;
            $produk->slot_available = max(0, $total - $used);
            $produk->slot_full = $produk->slot_available < 1;
        });

        return view('SuperAdmin.moderasi-produk.index', [
            'products' => $products,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }

    public function setujui(Request $request, Product $produk)
    {
        return DB::transaction(function () use ($produk) {
            $locked = Product::whereKey($produk->product_id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== Product::STATUS_PENDING) {
                return back()->with('toast', [
                    'message' => 'Hanya produk berstatus pending yang dapat disetujui.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            if ($locked->store_id === null) {
                return back()->with('toast', [
                    'message' => 'Produk tidak terhubung ke toko mana pun, tidak dapat disetujui.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            if (! SlotService::canAdd($locked->store_id)) {
                $total = SlotService::totalQuota($locked->store_id);
                $used = SlotService::usedSlots($locked->store_id);

                return back()->with('toast', [
                    'message' => sprintf('Kuota slot produk toko "%s" sudah penuh (%d/%d). Pemilik toko harus menambah slot terlebih dahulu.', $locked->store->nama_toko ?? '-', $used, $total),
                    'icon' => 'error',
                ]);
            }

            $lama = $locked->only(['status', 'alasan_penolakan']);

            $locked->update([
                'status' => Product::STATUS_AKTIF,
                'alasan_penolakan' => null,
            ]);

            ActivityLogger::log(
                'product.approve',
                Product::class,
                $locked->product_id,
                $lama,
                ['status' => Product::STATUS_AKTIF],
                sprintf('Menyetujui produk "%s" dari toko %s.', $locked->nama_produk, $locked->store->nama_toko ?? '-')
            );

            if ($locked->store) {
                Notification::create([
                    'user_id' => $locked->store->owner_id,
                    'aktor_id' => ActivityLogger::resolveActorId(),
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Produk Disetujui',
                    'pesan' => sprintf('Produk "%s" telah disetujui moderasi dan kini dapat tampil di Raliva.', $locked->nama_produk),
                    'url' => route('owner.produk'),
                ]);
            }
            Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Disetujui', sprintf('Produk "%s" disetujui.', $locked->nama_produk), route('superadmin.moderasi-produk'));

            return back()->with('toast', [
                'message' => sprintf('Produk %s disetujui dan kini dapat tampil.', $locked->nama_produk),
                'icon' => 'task_alt',
            ]);
        });
    }

    public function tolak(Request $request, Product $produk)
    {
        return DB::transaction(function () use ($request, $produk) {
            $locked = Product::whereKey($produk->product_id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== Product::STATUS_PENDING) {
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

            $lama = $locked->only(['status', 'alasan_penolakan']);

            $locked->update([
                'status' => Product::STATUS_DITOLAK,
                'alasan_penolakan' => $data['alasan'],
            ]);

            ActivityLogger::log(
                'product.reject',
                Product::class,
                $locked->product_id,
                $lama,
                ['status' => Product::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
                sprintf('Menolak produk "%s" dengan alasan: %s', $locked->nama_produk, $data['alasan'])
            );

            if ($locked->store) {
                Notification::create([
                    'user_id' => $locked->store->owner_id,
                    'aktor_id' => ActivityLogger::resolveActorId(),
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Produk Ditolak Moderasi',
                    'pesan' => sprintf('Produk "%s" ditolak moderasi. Alasan: %s. Silakan perbaiki lalu kirim ulang.', $locked->nama_produk, $data['alasan']),
                    'url' => route('owner.produk'),
                ]);
            }
            Notification::fireSelf(Notification::TIPE_SISTEM, 'Produk Ditolak', sprintf('Produk "%s" ditolak moderasi.', $locked->nama_produk), route('superadmin.moderasi-produk'));

            return back()->with('toast', [
                'message' => sprintf('Produk %s ditolak. Alasan dikirim ke pemilik toko.', $locked->nama_produk),
                'icon' => 'block',
            ]);
        });
    }
}
