<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengembalianDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::query()
            ->with(['order.store', 'payment', 'requester'])
            ->orderByRaw("CASE status WHEN 'requested' THEN 0 WHEN 'disetujui' THEN 1 ELSE 2 END")
            ->orderByDesc('diajukan_pada');

        $stats = [
            'semua' => Refund::count(),
            'requested' => Refund::where('status', Refund::STATUS_REQUESTED)->count(),
            'nominal_menunggu' => (float) Refund::where('status', Refund::STATUS_REQUESTED)->sum('jumlah'),
            'disetujui' => Refund::where('status', Refund::STATUS_DISETUJUI)->count(),
            'selesai' => Refund::where('status', Refund::STATUS_SELESAI)->count(),
            'ditolak' => Refund::where('status', Refund::STATUS_DITOLAK)->count(),
        ];

        $refunds = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.pengembalian-dana.index', [
            'refunds' => $refunds,
            'stats' => $stats,
        ]);
    }

    public function setujui(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_REQUESTED) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus menunggu yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $refund->only(['status', 'reviewed_by']);

        $refund->update([
            'status' => Refund::STATUS_DISETUJUI,
            'reviewed_by' => ActivityLogger::resolveActorId(),
        ]);

        ActivityLogger::log(
            'refund.approve',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_DISETUJUI],
            sprintf('Menyetujui refund %s sebesar Rp %s untuk pesanan %s.', $refund->tipe_refund, number_format((float) $refund->jumlah, 0, ',', '.'), $refund->order->nomor_order ?? '-')
        );

        $this->notifyPihak($refund, 'Refund Disetujui', sprintf('Pengajuan refund Anda (%s) sebesar Rp %s telah disetujui dan sedang diproses.', $refund->tipe_refund === Refund::TIPE_FULL ? 'penuh' : 'parsial', number_format((float) $refund->jumlah, 0, ',', '.')));
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Disetujui', sprintf('Refund Rp %s disetujui.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

        return back()->with('toast', [
            'message' => sprintf('Refund Rp %s disetujui dan diproses.', number_format((float) $refund->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_REQUESTED) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus menunggu yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $refund->only(['status', 'reviewed_by']);

        $refund->update([
            'status' => Refund::STATUS_DITOLAK,
            'reviewed_by' => ActivityLogger::resolveActorId(),
            'alasan_penolakan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'refund.reject',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak refund pesanan %s dengan alasan: %s', $refund->order->nomor_order ?? '-', $data['alasan'])
        );

        $this->notifyPihak($refund, 'Refund Ditolak', sprintf('Pengajuan refund Anda ditolak. Alasan: %s', $data['alasan']));
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Ditolak', sprintf('Refund Rp %s ditolak.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

        return back()->with('toast', [
            'message' => 'Pengajuan refund ditolak. Customer akan dinotifikasi.',
            'icon' => 'block',
        ]);
    }

    public function selesaikan(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_DISETUJUI) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus disetujui yang dapat diselesaikan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.required' => 'Bukti refund wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti refund harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti refund maksimal 5 MB.',
            'deskripsi_bukti.max' => 'Deskripsi bukti maksimal 1000 karakter.',
        ]);

        $path = $request->file('file_bukti')->store('bukti-refund/'.$refund->refund_id, 'public');

        $refund->loadMissing(['order.store']);

        $lama = $refund->only(['status']);

        $store = $refund->order?->store;

        try {
            DB::transaction(function () use ($refund, $path, $data, $lama, $store) {
                if ($store) {
                    $wallet = Wallet::where('store_id', $store->store_id)->lockForUpdate()->first();

                    if ($wallet) {
                        $saldoSebelum = (float) $wallet->saldo_tersedia;

                        if ($saldoSebelum < (float) $refund->jumlah) {
                            throw new \RuntimeException('Saldo toko tidak cukup untuk refund.');
                        }

                        $wallet->decrement('saldo_tersedia', (float) $refund->jumlah);

                        WalletTransaction::create([
                            'wallet_id' => $wallet->wallet_id,
                            'refund_id' => $refund->refund_id,
                            'jenis_transaksi' => WalletTransaction::JENIS_REFUND_KELUAR,
                            'jumlah' => (float) $refund->jumlah,
                            'saldo_sebelum' => $saldoSebelum,
                            'saldo_sesudah' => $saldoSebelum - (float) $refund->jumlah,
                            'keterangan' => sprintf('Refund %s untuk pesanan %s.', $refund->order->nomor_order ?? '-', $refund->tipe_refund),
                        ]);
                    }
                }

                $refund->update([
                    'status' => Refund::STATUS_SELESAI,
                    'selesai_pada' => now(),
                    'file_bukti' => $path,
                    'deskripsi_bukti' => $data['deskripsi_bukti'] ?? null,
                    'bukti_diupload_pada' => now(),
                ]);
            });
        } catch (\Throwable $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            if (str_contains($e->getMessage(), 'Saldo toko tidak cukup')) {
                return back()->with('toast', [
                    'message' => 'Saldo toko tidak cukup untuk menyelesaikan refund ini.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            throw $e;
        }

        ActivityLogger::log(
            'refund.complete',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_SELESAI, 'file_bukti' => $path],
            sprintf('Menyelesaikan refund sebesar Rp %s untuk pesanan %s (bukti terlampir).', number_format((float) $refund->jumlah, 0, ',', '.'), $refund->order->nomor_order ?? '-')
        );

        $this->notifyPihak($refund, 'Refund Selesai', sprintf('Dana refund sebesar Rp %s telah dikirim ke akun Anda.', number_format((float) $refund->jumlah, 0, ',', '.')));
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Selesai', sprintf('Refund Rp %s ditandai selesai.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

        return back()->with('toast', [
            'message' => 'Refund ditandai selesai dengan bukti terlampir.',
            'icon' => 'task_alt',
        ]);
    }

    private function notifyPihak(Refund $refund, string $judul, string $pesan): void
    {
        Notification::create([
            'user_id' => $refund->requested_by,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_PEMBAYARAN,
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => route('customer.order-tracking'),
        ]);

        $ownerId = optional($refund->order?->store)->owner_id;

        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PEMBAYARAN,
                'judul' => $judul.' (Toko Anda)',
                'pesan' => sprintf('%s | Pesanan %s.', $pesan, $refund->order->nomor_order ?? '-'),
                'url' => route('owner.pengembalian-dana'),
            ]);
        }
    }
}
