<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CustomerTopup;
use App\Models\Notification;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\CustomerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiTopupController extends Controller
{
    public function index()
    {
        $topups = CustomerTopup::query()
            ->with(['user', 'payment.paymentMethod', 'payment.account', 'payment.proofs'])
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'semua' => $topups->count(),
            'menunggu' => $topups->where('status', CustomerTopup::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'pending' => $topups->where('status', CustomerTopup::STATUS_PENDING)->count(),
            'nominal_menunggu' => (float) $topups->where('status', CustomerTopup::STATUS_MENUNGGU_VERIFIKASI)->sum('jumlah'),
            'terverifikasi' => $topups->where('status', CustomerTopup::STATUS_TERVERIFIKASI)->count(),
            'total_terverifikasi' => (float) $topups->where('status', CustomerTopup::STATUS_TERVERIFIKASI)->sum('jumlah'),
            'ditolak' => $topups->where('status', CustomerTopup::STATUS_DITOLAK)->count(),
        ];

        return view('SuperAdmin.verifikasi-topup.index', compact('topups', 'stats'));
    }

    public function setujui(Request $request, CustomerTopup $topup)
    {
        if ($topup->status !== CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) {
            return back()->with('toast', [
                'message' => 'Hanya topup berstatus menunggu verifikasi yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        DB::transaction(function () use ($topup) {
            $payment = $topup->payment()->lockForUpdate()->firstOrFail();
            $payment->update([
                'status' => Payment::STATUS_TERVERIFIKASI,
                'dibayar_pada' => now(),
            ]);

            CustomerWalletService::creditTopup($topup->user, $topup);

            $topup->update([
                'status' => CustomerTopup::STATUS_TERVERIFIKASI,
                'dibayar_pada' => now(),
            ]);
        });

        ActivityLogger::log(
            'customer.topup.approve',
            CustomerTopup::class,
            $topup->customer_topup_id,
            ['status' => CustomerTopup::STATUS_MENUNGGU_VERIFIKASI],
            ['status' => CustomerTopup::STATUS_TERVERIFIKASI],
            'Topup saldo disetujui: Rp '.number_format((float) $topup->jumlah, 0, ',', '.').' (#'.$topup->customer_topup_id.')',
        );

        NotificationService::fire(
            $topup->user_id,
            Notification::TIPE_WALLET,
            'Topup Saldo Disetujui',
            'Saldo Anda bertambah Rp '.number_format((float) $topup->jumlah, 0, ',', '.').'.',
            null,
            route('customer.saldo')
        );

        return back()->with('toast', [
            'message' => 'Topup disetujui, saldo customer bertambah.',
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, CustomerTopup $topup)
    {
        if (! in_array($topup->status, [CustomerTopup::STATUS_MENUNGGU_VERIFIKASI, CustomerTopup::STATUS_PENDING, CustomerTopup::STATUS_DITOLAK], true)) {
            return back()->with('toast', [
                'message' => 'Topup ini tidak dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $validated = $request->validate([
            'alasan' => ['required', 'string', 'min:10'],
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        DB::transaction(function () use ($topup) {
            $topup->update(['status' => CustomerTopup::STATUS_DITOLAK]);
            $topup->payment()->update(['status' => Payment::STATUS_DITOLAK]);
        });

        ActivityLogger::log(
            'customer.topup.reject',
            CustomerTopup::class,
            $topup->customer_topup_id,
            ['status' => $topup->status],
            ['status' => CustomerTopup::STATUS_DITOLAK],
            'Topup saldo ditolak: '.$validated['alasan'].' (#'.$topup->customer_topup_id.')',
        );

        NotificationService::fire(
            $topup->user_id,
            Notification::TIPE_WALLET,
            'Topup Saldo Ditolak',
            'Topup Anda ditolak: '.$validated['alasan'].'. Silakan upload ulang bukti pembayaran.',
            null,
            route('customer.saldo')
        );

        return back()->with('toast', [
            'message' => 'Topup ditolak, customer dihubungi.',
            'icon' => 'gpp_maybe',
        ]);
    }
}