<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\Notification;
use App\Models\PlatformBankAccount;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeringkatIklanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $tab = $request->query('tab', 'daftar');
        if (! in_array($tab, ['daftar', 'riwayat', 'pengajuan'], true)) $tab = 'daftar';

        $slotsQuery = AdSlot::with(['product:product_id,nama_produk', 'store:store_id,nama_toko'])
            ->orderByDesc('nominal_bid');

        $totalPendapatan = (float) (clone $slotsQuery)->whereIn('status', [AdSlot::STATUS_AKTIF, AdSlot::STATUS_TERJADWAL])->sum('nominal_bid');
        $slotAktif = (clone $slotsQuery)->where('status', AdSlot::STATUS_AKTIF)->count();
        $slotTerjadwal = (clone $slotsQuery)->where('status', AdSlot::STATUS_TERJADWAL)->count();
        $slotBerjalan = $slotAktif + $slotTerjadwal;
        $rataRataBid = $slotBerjalan > 0 ? $totalPendapatan / $slotBerjalan : 0;

        $top3 = (clone $slotsQuery)
            ->where('status', AdSlot::STATUS_AKTIF)
            ->whereNotNull('tanggal_mulai')
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_mulai', '<=', now()->toDateString())
            ->whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->orderBy('ad_slot_id')
            ->limit(3)->get();

        $today = now()->toDateString();
        if ($tab === 'pengajuan') {
            $slots = AdSlot::with(['product:product_id,nama_produk', 'store:store_id,nama_toko', 'bankAccount.bank'])
                ->where('status', AdSlot::STATUS_DITUNDA)
                ->orderByDesc('created_at')
                ->paginate(20)->withQueryString();
        } elseif ($tab === 'daftar') {
            $slots = (clone $slotsQuery)
                ->reorder()
                ->where(function ($q) use ($today) {
                    $q->where(function ($q2) use ($today) {
                        $q2->where('status', AdSlot::STATUS_AKTIF)
                            ->whereDate('tanggal_mulai', '<=', $today)
                            ->whereDate('tanggal_selesai', '>=', $today);
                    })->orWhere(function ($q2) use ($today) {
                        $q2->where('status', AdSlot::STATUS_TERJADWAL)
                            ->whereDate('tanggal_mulai', '>', $today);
                    });
                })
                ->whereNotNull('tanggal_mulai')
                ->whereNotNull('tanggal_selesai')
                ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [AdSlot::STATUS_AKTIF])
                ->orderByDesc('nominal_bid')
                ->orderBy('ad_slot_id')
                ->paginate(20)->withQueryString();
        } else {
            $slots = AdSlot::with(['product:product_id,nama_produk', 'store:store_id,nama_toko', 'bankAccount.bank'])
                ->where(function ($q) use ($today) {
                    $q->where('status', AdSlot::STATUS_NONAKTIF)
                        ->orWhere(function ($q2) use ($today) {
                            $q2->where('status', AdSlot::STATUS_AKTIF)->whereDate('tanggal_selesai', '<', $today);
                        });
                })
                ->orderByDesc('created_at')
                ->paginate(20)->withQueryString();
        }

        $products = Product::with('store:store_id,nama_toko')
            ->orderBy('nama_produk')
            ->get();

        $rekenings = PlatformBankAccount::with('bank')->whereNotNull('bank_id')->where('status', PlatformBankAccount::STATUS_AKTIF)->orderBy('nomor_rekening')->get();

        return view('SuperAdmin.peringkat.peringkat-iklan', [
            'slots' => $slots,
            'top3' => $top3,
            'tab' => $tab,
            'totalPendapatan' => $totalPendapatan,
            'slotAktif' => $slotAktif,
            'slotTerjadwal' => $slotTerjadwal,
            'rataRataBid' => $rataRataBid,
            'products' => $products,
            'rekenings' => $rekenings,
        ]);
    }

    public function store(Request $request)
    {
        abort(403, 'Pendaftaran slot via Super Admin dinonaktifkan. Gunakan alur Owner (bank+file+bukti).');
    }

    public function setujui(Request $request, AdSlot $slot)
    {
        if ($slot->status !== AdSlot::STATUS_DITUNDA) {
            return back()->with('toast', ['message' => 'Hanya slot menunggu yang dapat disetujui.', 'icon' => 'gpp_maybe']);
        }

        $slot->loadMissing(['store', 'product']);

        try {
            DB::transaction(function () use ($slot) {
                $locked = AdSlot::whereKey($slot->ad_slot_id)->lockForUpdate()->first();

                if (! $locked || $locked->status !== AdSlot::STATUS_DITUNDA) {
                    throw new \RuntimeException('Status slot sudah berubah oleh pihak lain.');
                }

                if (! $locked->store_id) {
                    throw new \RuntimeException('Slot tidak terhubung ke toko.');
                }

                $wallet = Wallet::where('store_id', $locked->store_id)->lockForUpdate()->first();

                if (! $wallet) {
                    throw new \RuntimeException('Wallet toko tidak ditemukan, saldo tidak dapat dipotong.');
                }

                $saldoSebelum = (float) $wallet->saldo_tersedia;

                if ($saldoSebelum < (float) $locked->nominal_bid) {
                    throw new \RuntimeException('Saldo toko tidak cukup untuk biaya iklan.');
                }

                $wallet->decrement('saldo_tersedia', (float) $locked->nominal_bid);

                WalletTransaction::create([
                    'wallet_id' => $wallet->wallet_id,
                    'ad_slot_id' => $locked->ad_slot_id,
                    'jenis_transaksi' => WalletTransaction::JENIS_BIAYA_IKLAN,
                    'jumlah' => (float) $locked->nominal_bid,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldoSebelum - (float) $locked->nominal_bid,
                    'keterangan' => sprintf('Biaya iklan peringkat "%s" Rp %s periode %s s/d %s.', $locked->product->nama_produk ?? '-', number_format((float) $locked->nominal_bid, 0, ',', '.'), $locked->tanggal_mulai ? \Illuminate\Support\Carbon::parse($locked->tanggal_mulai)->translatedFormat('d M Y') : '-', $locked->tanggal_selesai ? \Illuminate\Support\Carbon::parse($locked->tanggal_selesai)->translatedFormat('d M Y') : '-'),
                ]);

                $today = now()->toDateString();
                $mulai = $locked->tanggal_mulai ? \Illuminate\Support\Carbon::parse($locked->tanggal_mulai)->toDateString() : $today;
                $selesai = $locked->tanggal_selesai ? \Illuminate\Support\Carbon::parse($locked->tanggal_selesai)->toDateString() : \Illuminate\Support\Carbon::parse($mulai)->addDays(7)->toDateString();
                $statusBaru = $mulai <= $today ? AdSlot::STATUS_AKTIF : AdSlot::STATUS_TERJADWAL;

                $locked->update([
                    'status' => $statusBaru,
                    'payment_status' => AdSlot::PAYMENT_TERVERIFIKASI,
                    'paid_at' => now(),
                    'handled_by' => ActivityLogger::resolveActorId(),
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('toast', ['message' => 'Gagal menyetujui iklan: '.$e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        $slot->refresh();

        ActivityLogger::log('ad_slot.approve', AdSlot::class, $slot->ad_slot_id, ['status' => AdSlot::STATUS_DITUNDA], ['status' => $slot->status], 'Menyetujui iklan peringkat Rp '.number_format((float) $slot->nominal_bid, 0, ',', '.'));

        $ownerId = $slot->store?->owner_id;
        if ($ownerId) {
            if ($slot->status === AdSlot::STATUS_TERJADWAL) {
                $judul = 'Iklan Disetujui & Terjadwal';
                $pesan = sprintf('Iklan "%s" disetujui dan akan tayang otomatis mulai %s s/d %s.', $slot->product->nama_produk ?? '-', $slot->tanggal_mulai ? \Illuminate\Support\Carbon::parse($slot->tanggal_mulai)->translatedFormat('d M Y') : '-', $slot->tanggal_selesai ? \Illuminate\Support\Carbon::parse($slot->tanggal_selesai)->translatedFormat('d M Y') : '-');
            } else {
                $judul = 'Iklan Disetujui';
                $pesan = sprintf('Iklan "%s" disetujui dan kini tayang peringkat %s s/d %s.', $slot->product->nama_produk ?? '-', $slot->tanggal_mulai ? \Illuminate\Support\Carbon::parse($slot->tanggal_mulai)->translatedFormat('d M Y') : '-', $slot->tanggal_selesai ? \Illuminate\Support\Carbon::parse($slot->tanggal_selesai)->translatedFormat('d M Y') : '-');
            }

            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PROMO,
                'judul' => $judul,
                'pesan' => $pesan,
                'url' => route('owner.peringkat-iklan'),
            ]);
        }

        $toast = $slot->status === AdSlot::STATUS_TERJADWAL
            ? 'Iklan disetujui dan terjadwal menunggu tanggal mulai.'
            : 'Iklan disetujui dan aktif.';

        return back()->with('toast', ['message' => $toast, 'icon' => 'task_alt']);
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
