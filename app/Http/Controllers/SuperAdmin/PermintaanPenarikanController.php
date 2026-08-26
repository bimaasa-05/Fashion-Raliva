<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanPenarikanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $stats = [
            'menunggu' => Withdrawal::where('status', Withdrawal::STATUS_PENDING)->count(),
            'nominal_menunggu' => (float) Withdrawal::where('status', Withdrawal::STATUS_PENDING)->sum('jumlah'),
            'total_semua' => (float) Withdrawal::whereIn('status', [Withdrawal::STATUS_DISETUJUI, Withdrawal::STATUS_DIBAYAR])->sum('jumlah'),
        ];

        $withdrawals = Withdrawal::query()
            ->with(['store.owner:user_id,nama_lengkap', 'wallet', 'bankAccount.bank'])
            ->when(
                in_array($status, [Withdrawal::STATUS_PENDING, Withdrawal::STATUS_DISETUJUI, Withdrawal::STATUS_DITOLAK, Withdrawal::STATUS_DIBAYAR], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'disetujui' THEN 1 WHEN 'dibayar' THEN 2 ELSE 3 END")
            ->orderByDesc('diajukan_pada')
            ->get();

        return view('SuperAdmin.permintaan-penarikan.index', [
            'withdrawals' => $withdrawals,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }

    public function setujui(Request $request, Withdrawal $penarikan)
    {
        if ($penarikan->status !== Withdrawal::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya pengajuan berstatus menunggu yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $penarikan->only(['status', 'reviewed_by']);

        DB::transaction(function () use ($penarikan) {
            $wallet = $penarikan->wallet()->lockForUpdate()->firstOrFail();

            if ((float) $wallet->saldo_tersedia < (float) $penarikan->jumlah) {
                throw new \RuntimeException('Saldo tersedia tidak mencukupi untuk pencairan ini.');
            }

            $wallet->decrement('saldo_tersedia', $penarikan->jumlah);
            $wallet->increment('saldo_tertahan', $penarikan->jumlah);

            $penarikan->update([
                'status' => Withdrawal::STATUS_DISETUJUI,
                'reviewed_by' => ActivityLogger::resolveActorId(),
                'ditinjau_pada' => now(),
            ]);
        });

        ActivityLogger::log(
            'withdrawal.approve',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            $lama,
            ['status' => Withdrawal::STATUS_DISETUJUI],
            sprintf('Menyetujui pencairan Rp %s untuk toko "%s" (dana dikunci).', number_format((float) $penarikan->jumlah, 0, ',', '.'), $penarikan->store->nama_toko ?? '-')
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Pencairan Disetujui',
                'pesan' => sprintf('Pencairan sebesar Rp %s telah disetujui dan sedang diproses.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
            ]);
        }

        return back()->with('toast', [
            'message' => sprintf('Pencairan Rp %s disetujui dan dana dikunci untuk diproses.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Withdrawal $penarikan)
    {
        if ($penarikan->status !== Withdrawal::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya pengajuan berstatus menunggu yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $penarikan->only(['status', 'reviewed_by']);

        $penarikan->update([
            'status' => Withdrawal::STATUS_DITOLAK,
            'reviewed_by' => ActivityLogger::resolveActorId(),
            'ditinjau_pada' => now(),
            'alasan_penolakan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'withdrawal.reject',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            $lama,
            ['status' => Withdrawal::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak pencairan toko "%s" dengan alasan: %s', $penarikan->store->nama_toko ?? '-', $data['alasan'])
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Pencairan Ditolak',
                'pesan' => sprintf('Pengajuan pencairan Rp %s ditolak. Alasan: %s', number_format((float) $penarikan->jumlah, 0, ',', '.'), $data['alasan']),
            ]);
        }

        return back()->with('toast', [
            'message' => 'Pengajuan pencairan ditolak.',
            'icon' => 'block',
        ]);
    }

    public function tandaiDibayar(Request $request, Withdrawal $penarikan)
    {
        if ($penarikan->status !== Withdrawal::STATUS_DISETUJUI) {
            return back()->with('toast', [
                'message' => 'Hanya pencairan berstatus disetujui yang dapat ditandai dibayar.',
                'icon' => 'gpp_maybe',
            ]);
        }

        DB::transaction(function () use ($penarikan) {
            $wallet = $penarikan->wallet()->lockForUpdate()->firstOrFail();
            $saldoSebelum = (float) $wallet->saldo_tertahan;

            $wallet->decrement('saldo_tertahan', $penarikan->jumlah);

            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'withdrawal_id' => $penarikan->withdrawal_id,
                'jenis_transaksi' => WalletTransaction::JENIS_WITHDRAWAL,
                'jumlah' => $penarikan->jumlah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSebelum - (float) $penarikan->jumlah,
                'keterangan' => sprintf('Pencairan dana ke rekening %s (%s).', $penarikan->bankAccount->nomor_rekening ?? '-', $penarikan->bankAccount->bank->nama_bank ?? '-'),
            ]);

            $penarikan->update([
                'status' => Withdrawal::STATUS_DIBAYAR,
                'dibayar_pada' => now(),
            ]);
        });

        ActivityLogger::log(
            'withdrawal.paid',
            Withdrawal::class,
            $penarikan->withdrawal_id,
            ['status' => Withdrawal::STATUS_DISETUJUI],
            ['status' => Withdrawal::STATUS_DIBAYAR],
            sprintf('Menandai pencairan Rp %s untuk toko "%s" sebagai dibayar.', number_format((float) $penarikan->jumlah, 0, ',', '.'), $penarikan->store->nama_toko ?? '-')
        );

        if ($penarikan->store) {
            Notification::create([
                'user_id' => $penarikan->store->owner_id,
                'tipe' => Notification::TIPE_WALLET,
                'judul' => 'Pencairan Dibayar',
                'pesan' => sprintf('Dana pencairan sebesar Rp %s telah dikirim ke rekening Anda.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
            ]);
        }

        return back()->with('toast', [
            'message' => sprintf('Pencairan Rp %s ditandai sudah dibayar.', number_format((float) $penarikan->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }
}
