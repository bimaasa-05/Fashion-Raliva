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
        $status = $request->query('status', 'semua');

        $map = [
            'pending' => Payment::STATUS_PENDING,
            'menunggu_verifikasi' => Payment::STATUS_MENUNGGU_VERIFIKASI,
            'terverifikasi' => Payment::STATUS_TERVERIFIKASI,
            'ditolak' => Payment::STATUS_DITOLAK,
            'kadaluarsa' => Payment::STATUS_KADALUARSA,
        ];

        $rawStatus = $map[$status] ?? null;

        $stats = [
            'total' => Payment::count(),
            Payment::STATUS_PENDING => Payment::where('status', Payment::STATUS_PENDING)->count(),
            Payment::STATUS_MENUNGGU_VERIFIKASI => Payment::where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->count(),
            Payment::STATUS_TERVERIFIKASI => Payment::where('status', Payment::STATUS_TERVERIFIKASI)->count(),
            Payment::STATUS_DITOLAK => Payment::where('status', Payment::STATUS_DITOLAK)->count(),
            Payment::STATUS_KADALUARSA => Payment::where('status', Payment::STATUS_KADALUARSA)->count(),
        ];

        $payments = Payment::query()
            ->with(['paymentMethod:payment_method_id,nama_metode,kode_metode', 'checkout.user:user_id,nama_lengkap,email', 'checkout.orders.store:store_id,nama_toko'])
            ->when($rawStatus, fn ($q) => $q->where('status', $rawStatus))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $payments->getCollection()->transform(function (Payment $payment) {
            $payment->nama_toko = $payment->checkout?->orders?->first()?->store?->nama_toko ?? '-';
            $payment->waktu_relatif = $payment->created_at
                ? Carbon::parse($payment->created_at)->locale('id')->diffForHumans()
                : '-';

            return $payment;
        });

        return view('SuperAdmin.data-pembayaran.index', [
            'payments' => $payments,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }
}
