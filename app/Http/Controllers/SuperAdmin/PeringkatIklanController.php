<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Notification;
use App\Models\PlatformBankAccount;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\ActivityLogger;
use App\Support\PeringkatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        $rekenings = PlatformBankAccount::with('bank')->where('status', PlatformBankAccount::STATUS_AKTIF)->orderBy('nomor_rekening')->get();

        $tiers = PeringkatService::defaultTiers();
        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && $decoded !== []) {
                $tiers = $decoded;
            }
        }

        return view('SuperAdmin.peringkat.peringkat-iklan', [
            'slots' => $slots,
            'totalPendapatan' => $totalPendapatan,
            'slotAktif' => $slotAktif,
            'rataRataBid' => $rataRataBid,
            'products' => $products,
            'rekenings' => $rekenings,
            'tiers' => $tiers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'nominal_bid' => 'required|numeric|min:100000',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'nominal_bid.required' => 'Nominal bayaran wajib diisi.',
            'nominal_bid.min' => 'Minimal Rp 100.000.',
        ]);

        $product = Product::find($data['product_id']);

        $hari = PeringkatService::resolveHari((int) $data['nominal_bid']);
        $mulai = $data['tanggal_mulai'] ?? now()->toDateString();
        $selesai = $data['tanggal_selesai'] ?? now()->addDays($hari)->toDateString();

        $slot = AdSlot::create([
            'product_id' => $data['product_id'],
            'store_id' => $product->store_id,
            'nominal_bid' => $data['nominal_bid'],
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'status' => AdSlot::STATUS_AKTIF,
            'payment_status' => AdSlot::PAYMENT_TERVERIFIKASI,
            'paid_at' => now(),
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

    public function verifikasiPembayaran(Request $request, AdSlot $slot)
    {
        if ($slot->payment_status === AdSlot::PAYMENT_TERVERIFIKASI) {
            return back()->with('toast', ['message' => 'Pembayaran sudah terverifikasi.', 'icon' => 'info']);
        }

        if ($slot->status !== AdSlot::STATUS_DITUNDA) {
            return back()->with('toast', ['message' => 'Hanya slot menunggu yang dapat diverifikasi.', 'icon' => 'gpp_maybe']);
        }

        $slot->update([
            'payment_status' => AdSlot::PAYMENT_TERVERIFIKASI,
            'paid_at' => now(),
            'handled_by' => ActivityLogger::resolveActorId(),
        ]);

        ActivityLogger::log('ad_slot.verify', AdSlot::class, $slot->ad_slot_id, ['payment_status' => AdSlot::PAYMENT_MENUNGGU], ['payment_status' => AdSlot::PAYMENT_TERVERIFIKASI], 'Verifikasi pembayaran iklan Rp '.number_format((float) $slot->nominal_bid, 0, ',', '.'));

        $ownerId = $slot->store?->owner_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PROMO,
                'judul' => 'Pembayaran Iklan Terverifikasi',
                'pesan' => sprintf('Pembayaran iklan "%s" Rp %s terverifikasi. Menunggu persetujuan.', $slot->product->nama_produk ?? '-', number_format((float) $slot->nominal_bid, 0, ',', '.')),
                'url' => route('owner.peringkat-iklan'),
            ]);
        }

        return back()->with('toast', ['message' => 'Pembayaran iklan berhasil diverifikasi.', 'icon' => 'task_alt']);
    }

    public function setujui(Request $request, AdSlot $slot)
    {
        if ($slot->status !== AdSlot::STATUS_DITUNDA) {
            return back()->with('toast', ['message' => 'Hanya slot menunggu yang dapat disetujui.', 'icon' => 'gpp_maybe']);
        }

        if ($slot->payment_status !== AdSlot::PAYMENT_TERVERIFIKASI) {
            return back()->with('toast', ['message' => 'Verifikasi pembayaran terlebih dahulu sebelum menyetujui.', 'icon' => 'gpp_maybe']);
        }

        $slot->loadMissing(['store', 'product']);

        try {
            DB::transaction(function () use ($slot) {
                $wallet = Wallet::where('store_id', $slot->store_id)->lockForUpdate()->first();

                if ($wallet) {
                    $saldoSebelum = (float) $wallet->saldo_tersedia;

                    WalletTransaction::create([
                        'wallet_id' => $wallet->wallet_id,
                        'ad_slot_id' => $slot->ad_slot_id,
                        'jenis_transaksi' => WalletTransaction::JENIS_BIAYA_IKLAN,
                        'jumlah' => (float) $slot->nominal_bid,
                        'saldo_sebelum' => $saldoSebelum,
                        'saldo_sesudah' => $saldoSebelum,
                        'keterangan' => sprintf('Biaya iklan peringkat "%s" Rp %s periode %s s/d %s.', $slot->product->nama_produk ?? '-', number_format((float) $slot->nominal_bid, 0, ',', '.'), $slot->tanggal_mulai ? \Illuminate\Support\Carbon::parse($slot->tanggal_mulai)->translatedFormat('d M Y') : '-', $slot->tanggal_selesai ? \Illuminate\Support\Carbon::parse($slot->tanggal_selesai)->translatedFormat('d M Y') : '-'),
                    ]);
                }

                $hari = PeringkatService::resolveHari((int) $slot->nominal_bid);

                $slot->update([
                    'status' => AdSlot::STATUS_AKTIF,
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->addDays($hari)->toDateString(),
                    'handled_by' => ActivityLogger::resolveActorId(),
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => 'Gagal menyetujui iklan: '.$e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        ActivityLogger::log('ad_slot.approve', AdSlot::class, $slot->ad_slot_id, ['status' => AdSlot::STATUS_DITUNDA], ['status' => AdSlot::STATUS_AKTIF], 'Menyetujui iklan peringkat Rp '.number_format((float) $slot->nominal_bid, 0, ',', '.'));

        $ownerId = $slot->store?->owner_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PROMO,
                'judul' => 'Iklan Disetujui',
                'pesan' => sprintf('Iklan "%s" disetujui dan aktif peringkat %s s/d %s.', $slot->product->nama_produk ?? '-', $slot->tanggal_mulai ? \Illuminate\Support\Carbon::parse($slot->tanggal_mulai)->translatedFormat('d M Y') : '-', $slot->tanggal_selesai ? \Illuminate\Support\Carbon::parse($slot->tanggal_selesai)->translatedFormat('d M Y') : '-'),
                'url' => route('owner.peringkat-iklan'),
            ]);
        }

        return back()->with('toast', ['message' => 'Iklan disetujui dan aktif.', 'icon' => 'task_alt']);
    }

    public function tolak(Request $request, AdSlot $slot)
    {
        if ($slot->status !== AdSlot::STATUS_DITUNDA) {
            return back()->with('toast', ['message' => 'Hanya slot menunggu yang dapat ditolak.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan minimal 10 karakter.',
        ]);

        $slot->update([
            'payment_status' => AdSlot::PAYMENT_DITOLAK,
            'status' => AdSlot::STATUS_NONAKTIF,
            'alasan_penolakan' => $data['alasan'],
            'handled_by' => ActivityLogger::resolveActorId(),
        ]);

        ActivityLogger::log('ad_slot.reject', AdSlot::class, $slot->ad_slot_id, ['status' => AdSlot::STATUS_DITUNDA], ['status' => AdSlot::STATUS_NONAKTIF], 'Menolak iklan: '.$data['alasan']);

        $ownerId = $slot->store?->owner_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PROMO,
                'judul' => 'Iklan Ditolak',
                'pesan' => sprintf('Pengajuan iklan "%s" ditolak. Alasan: %s', $slot->product->nama_produk ?? '-', $data['alasan']),
                'url' => route('owner.peringkat-iklan'),
            ]);
        }

        return back()->with('toast', ['message' => 'Iklan ditolak.', 'icon' => 'block']);
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
