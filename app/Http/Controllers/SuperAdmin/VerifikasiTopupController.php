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
        $base = CustomerTopup::query()
            ->with(['user', 'payment.paymentMethod', 'payment.account', 'payment.proofs']);

        $stats = [
            'semua' => (clone $base)->count(),
            'menunggu' => (clone $base)->where('status', CustomerTopup::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'pending' => (clone $base)->where('status', CustomerTopup::STATUS_PENDING)->count(),
            'nominal_menunggu' => (float) (clone $base)->where('status', CustomerTopup::STATUS_MENUNGGU_VERIFIKASI)->sum('jumlah'),
            'terverifikasi' => (clone $base)->where('status', CustomerTopup::STATUS_TERVERIFIKASI)->count(),
            'total_terverifikasi' => (float) (clone $base)->where('status', CustomerTopup::STATUS_TERVERIFIKASI)->sum('jumlah'),
            'ditolak' => (clone $base)->where('status', CustomerTopup::STATUS_DITOLAK)->count(),
            'dibatalkan' => (clone $base)->where('status', CustomerTopup::STATUS_DIBATALKAN)->count(),
            'kadaluarsa' => (clone $base)->where('status', CustomerTopup::STATUS_KADALUARSA)->count(),
        ];

        $topups = $base
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('SuperAdmin.verifikasi-topup.index', compact('topups', 'stats'));
    }

    public function setujui(Request $request, CustomerTopup $topup)
    {
        if ($topup->status !== CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) {
            return back()->with('toast', [
                'message' => __('Hanya topup berstatus menunggu verifikasi yang dapat disetujui.'),
                'icon' => 'gpp_maybe',
            ]);
        }

        try {
            DB::transaction(function () use ($topup) {
                $locked = CustomerTopup::whereKey($topup->customer_topup_id)->lockForUpdate()->firstOrFail();
                if ($locked->status !== CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) {
                    throw new \RuntimeException(__('Topup ini sudah diproses pihak lain.'));
                }

                $payment = $locked->payment()->lockForUpdate()->firstOrFail();
                $payment->update([
                    'status' => Payment::STATUS_TERVERIFIKASI,
                    'dibayar_pada' => now(),
                ]);

                CustomerWalletService::creditTopup($locked->user, $locked);

                $locked->update([
                    'status' => CustomerTopup::STATUS_TERVERIFIKASI,
                    'dibayar_pada' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', [
                'message' => $e->getMessage(),
                'icon' => 'gpp_maybe',
            ]);
        }

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
            __('Topup Saldo Disetujui'),
            sprintf(__('Saldo Anda bertambah Rp %s.'), number_format((float) $topup->jumlah, 0, ',', '.')),
            null,
            route('customer.saldo')
        );

        return back()->with('toast', [
            'message' => __('Topup disetujui, saldo customer bertambah.'),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, CustomerTopup $topup)
    {
        if (! in_array($topup->status, [CustomerTopup::STATUS_MENUNGGU_VERIFIKASI, CustomerTopup::STATUS_PENDING], true)) {
            return back()->with('toast', [
                'message' => __('Topup ini tidak dapat ditolak.'),
                'icon' => 'gpp_maybe',
            ]);
        }

        $validated = $request->validate([
            'alasan' => ['required', 'string', 'min:10'],
        ], [
            'alasan.required' => __('Alasan penolakan wajib diisi.'),
            'alasan.min' => __('Alasan penolakan minimal 10 karakter.'),
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
            __('Topup Saldo Ditolak'),
            sprintf(__('Topup Anda ditolak: %s. Silakan upload ulang bukti pembayaran.'), $validated['alasan']),
            null,
            route('customer.saldo')
        );

        return back()->with('toast', [
            'message' => __('Topup ditolak, customer dihubungi.'),
            'icon' => 'gpp_maybe',
        ]);
    }
}