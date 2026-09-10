<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Notification;
use App\Models\Product;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PeringkatIklanController extends Controller
{
    public function index()
    {
        $slotsQuery = AdSlot::with(['product:product_id,nama_produk', 'store:store_id,nama_toko'])
            ->orderByDesc('nominal_bid');

        $totalPendapatan = (float) (clone $slotsQuery)->where('status', 'aktif')->sum('nominal_bid');
        $slotAktif = (clone $slotsQuery)->where('status', 'aktif')->count();
        $rataRataBid = $slotAktif > 0 ? $totalPendapatan / $slotAktif : 0;

        $slots = $slotsQuery->paginate(20)->withQueryString();

        $products = Product::with('store:store_id,nama_toko')
            ->orderBy('nama_produk')
            ->get();

        return view('SuperAdmin.peringkat.peringkat-iklan', [
            'slots' => $slots,
            'totalPendapatan' => $totalPendapatan,
            'slotAktif' => $slotAktif,
            'rataRataBid' => $rataRataBid,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'nominal_bid' => 'required|numeric|min:100000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'nominal_bid.required' => 'Nominal bayaran wajib diisi.',
            'nominal_bid.min' => 'Minimal Rp 100.000.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal berakhir wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        $product = Product::find($data['product_id']);

        $slot = AdSlot::create([
            'product_id' => $data['product_id'],
            'store_id' => $product->store_id,
            'nominal_bid' => $data['nominal_bid'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'status' => AdSlot::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'ad_slot.create',
            AdSlot::class,
            $slot->ad_slot_id,
            null,
            $slot->toArray(),
            'Mendaftarkan slot iklan baru untuk produk: '.$product->nama_produk
        );

        Notification::fireSelf(Notification::TIPE_PROMO, 'Slot Iklan Dibuat', 'Slot iklan untuk produk "'.$product->nama_produk.'" didaftarkan.', route('superadmin.peringkat-iklan'));

        if ($product->store?->owner_id) {
            Notification::create([
                'user_id' => $product->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PROMO,
                'judul' => 'Iklan Produk Terdaftar',
                'pesan' => sprintf('Produk "%s" didaftarkan ke slot iklan platform oleh Super Admin.', $product->nama_produk),
                'url' => route('owner.produk'),
            ]);
        }

        return back()->with('toast', [
            'message' => 'Slot iklan berhasil didaftarkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function destroy(AdSlot $slot)
    {
        $nama = $slot->product->nama_produk ?? 'Produk #'.$slot->product_id;

        ActivityLogger::log(
            'ad_slot.delete',
            AdSlot::class,
            $slot->ad_slot_id,
            $slot->toArray(),
            null,
            'Menghapus slot iklan: '.$nama
        );

        $slot->delete();

        Notification::fireSelf(Notification::TIPE_PROMO, 'Slot Iklan Dihapus', 'Slot iklan "'.$nama.'" dihapus.', route('superadmin.peringkat-iklan'));

        return back()->with('toast', [
            'message' => 'Slot iklan "'.$nama.'" berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }
}
