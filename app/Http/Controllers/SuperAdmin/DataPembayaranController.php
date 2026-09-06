<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DataPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with(['paymentMethod:payment_method_id,nama_metode,kode_metode', 'checkout.user:user_id,nama_lengkap,email', 'checkout.orders.store:store_id,nama_toko'])
            ->orderByDesc('created_at')
            ->get();

        $payments->transform(function (Payment $payment) {
            $payment->nama_toko = $payment->checkout?->orders?->first()?->store?->nama_toko ?? '-';
            $payment->waktu_relatif = $payment->created_at
                ? Carbon::parse($payment->created_at)->locale('id')->diffForHumans()
                : '-';

            return $payment;
        });

        $stats = [
            'semua' => $payments->count(),
            'terverifikasi' => $payments->where('status', Payment::STATUS_TERVERIFIKASI)->count(),
            'menunggu_verifikasi' => $payments->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'pending' => $payments->where('status', Payment::STATUS_PENDING)->count(),
            'ditolak' => $payments->where('status', Payment::STATUS_DITOLAK)->count(),
            'kadaluarsa' => $payments->where('status', Payment::STATUS_KADALUARSA)->count(),
        ];

        return view('SuperAdmin.data-pembayaran.index', [
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }
}
