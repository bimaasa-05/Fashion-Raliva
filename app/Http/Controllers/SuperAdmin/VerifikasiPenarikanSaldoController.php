<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CustomerWithdrawal;
use App\Models\Notification;
use App\Support\ActivityLogger;
use App\Support\CustomerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPenarikanSaldoController extends Controller
{
    public function index(Request $request)
    {
        $penarikans = CustomerWithdrawal::query()
            ->with(['user:user_id,nama_lengkap,email', 'bank:bank_id,nama_bank'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'disetujui' THEN 1 WHEN 'dibayar' THEN 2 ELSE 3 END")
            ->orderByDesc('diajukan_pada')
            ->get();

        $stats = [
            'semua' => $penarikans->count(),
            'pending' => $penarikans->where('status', CustomerWithdrawal::STATUS_PENDING)->count(),
            'nominal_menunggu' => (float) $penarikans->where('status', CustomerWithdrawal::STATUS_PENDING)->sum('jumlah_bersih'),
            'disetujui' => $penarikans->where('status', CustomerWithdrawal::STATUS_DISETUJUI)->count(),
            'dibayar' => $penarikans->where('status', CustomerWithdrawal::STATUS_DIBAYAR)->count(),
            'ditolak' => $penarikans->where('status', CustomerWithdrawal::STATUS_DITOLAK)->count(),
            'fee_terkumpul' => (float) $penarikans->where('status', CustomerWithdrawal::STATUS_DIBAYAR)->sum('fee'),
        ];

        return view('SuperAdmin.verifikasi-penarikan-saldo.index', [
            'penarikans' => $penarikans,
            'stats' => $stats,
        ]);
    }

    public function setujui(Request $request, CustomerWithdrawal $penarikan)
    {
        if ($penarikan->status !== CustomerWithdrawal::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya pengajuan berstatus menunggu yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $penarikan->only(['status']);

        $penarikan->update([
            'status' => CustomerWithdrawal::STATUS_DISETUJUI,
            'diproses_pada' => now(),
        ]);

        ActivityLogger::log(
            'customer-withdrawal.approve',
            CustomerWithdrawal::class,
            $penarikan->customer_withdrawal_id,
            $lama,
            ['status' => CustomerWithdrawal::STATUS_DISETUJUI],
            sprintf('Menyetujui penarikan saldo Rp %s (bersih Rp %s) milik %s.', number_format((float) $penarikan->jumlah, 0, ',', '.'), number_format((float) $penarikan->jumlah_bersih, 0, ',', '.'), $penarikan->user?->nama_lengkap ?? '-')
        );

        Notification::create([
            'user_id' => $penarikan->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_WALLET,
            'judul' => 'Penarikan Disetujui',
            'pesan' => sprintf('Penarikan saldo sebesar Rp %s telah disetujui dan segera ditransfer.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')),
            'url' => route('customer.saldo.tarik.show', $penarikan->customer_withdrawal_id),
        ]);
        Notification::fireSelf(Notification::TIPE_WALLET, 'Penarikan Saldo Disetujui', sprintf('Penarikan saldo Rp %s disetujui.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')), route('superadmin.verifikasi-penarikan-saldo'));

        return back()->with('toast', [
            'message' => sprintf('Penarikan Rp %s disetujui, lanjutkan transfer manual lalu tandai dibayar.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, CustomerWithdrawal $penarikan)
    {
        if ($penarikan->status !== CustomerWithdrawal::STATUS_PENDING) {
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

        $lama = $penarikan->only(['status']);

        DB::transaction(function () use ($penarikan, $data) {
            $penarikan->update([
                'status' => CustomerWithdrawal::STATUS_DITOLAK,
                'diproses_pada' => now(),
                'catatan_admin' => $data['alasan'],
            ]);

            CustomerWalletService::creditWithdrawalRefund(
                $penarikan->user,
                $penarikan,
                (float) $penarikan->jumlah
            );
        });

        ActivityLogger::log(
            'customer-withdrawal.reject',
            CustomerWithdrawal::class,
            $penarikan->customer_withdrawal_id,
            $lama,
            ['status' => CustomerWithdrawal::STATUS_DITOLAK, 'catatan_admin' => $data['alasan']],
            sprintf('Menolak penarikan saldo %s dengan alasan: %s', $penarikan->user?->nama_lengkap ?? '-', $data['alasan'])
        );

        Notification::create([
            'user_id' => $penarikan->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_WALLET,
            'judul' => 'Penarikan Ditolak',
            'pesan' => sprintf('Pengajuan penarikan Rp %s ditolak dan saldo dikembalikan. Alasan: %s', number_format((float) $penarikan->jumlah, 0, ',', '.'), $data['alasan']),
            'url' => route('customer.saldo.tarik.show', $penarikan->customer_withdrawal_id),
        ]);
        Notification::fireSelf(Notification::TIPE_WALLET, 'Penarikan Saldo Ditolak', sprintf('Penarikan saldo Rp %s ditolak.', number_format((float) $penarikan->jumlah, 0, ',', '.')), route('superadmin.verifikasi-penarikan-saldo'));

        return back()->with('toast', [
            'message' => 'Pengajuan penarikan ditolak dan saldo dikembalikan.',
            'icon' => 'block',
        ]);
    }

    public function tandaiDibayar(Request $request, CustomerWithdrawal $penarikan)
    {
        if ($penarikan->status !== CustomerWithdrawal::STATUS_DISETUJUI) {
            return back()->with('toast', [
                'message' => 'Hanya penarikan berstatus disetujui yang dapat ditandai dibayar.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $penarikan->only(['status']);

        $penarikan->update([
            'status' => CustomerWithdrawal::STATUS_DIBAYAR,
            'diproses_pada' => now(),
        ]);

        ActivityLogger::log(
            'customer-withdrawal.paid',
            CustomerWithdrawal::class,
            $penarikan->customer_withdrawal_id,
            $lama,
            ['status' => CustomerWithdrawal::STATUS_DIBAYAR],
            sprintf('Menandai penarikan saldo Rp %s milik %s sebagai dibayar.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.'), $penarikan->user?->nama_lengkap ?? '-')
        );

        Notification::create([
            'user_id' => $penarikan->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_WALLET,
            'judul' => 'Penarikan Dibayar',
            'pesan' => sprintf('Dana penarikan sebesar Rp %s telah ditransfer ke tujuan Anda.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')),
            'url' => route('customer.saldo.tarik.show', $penarikan->customer_withdrawal_id),
        ]);
        Notification::fireSelf(Notification::TIPE_WALLET, 'Penarikan Saldo Dibayar', sprintf('Penarikan saldo Rp %s ditandai dibayar.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')), route('superadmin.verifikasi-penarikan-saldo'));

        return back()->with('toast', [
            'message' => sprintf('Penarikan Rp %s ditandai sudah dibayar.', number_format((float) $penarikan->jumlah_bersih, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }
}
