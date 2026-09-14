<?php

namespace App\Support;

use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentExpiry
{
    /**
     * Batalkan otomatis pembayaran pending yang melewati batas_waktu.
     *
     * Idempotent: hanya menyentuh payment berstatus pending yang checkout-nya
     * masih memiliki seluruh order pending_payment (tidak ada yang sudah
     * naik status). Aman dijalankan berulang (cron/middleware/lazy-call).
     *
     * @return int jumlah pembayaran yang di-expire
     */
    public static function expireOverdue(): int
    {
        $expired = 0;

        $payments = Payment::query()
            ->where('status', Payment::STATUS_PENDING)
            ->whereNotNull('batas_waktu')
            ->where('batas_waktu', '<', now())
            ->with('checkout.orders')
            ->get();

        foreach ($payments as $payment) {
            $checkout = $payment->checkout;

            if (! $checkout || $checkout->orders->isEmpty()) {
                continue;
            }

            $semuaMasihPending = ! $checkout->orders->contains(
                fn (Order $o) => $o->status !== Order::STATUS_PENDING_PAYMENT
            );

            if (! $semuaMasihPending) {
                continue;
            }

            DB::transaction(function () use ($payment, $checkout) {
                $payment->update(['status' => Payment::STATUS_KADALUARSA]);

                if ($checkout->status === Checkout::STATUS_PENDING) {
                    $checkout->update(['status' => Checkout::STATUS_KADALUARSA]);
                }

                Order::where('checkout_id', $checkout->checkout_id)
                    ->where('status', Order::STATUS_PENDING_PAYMENT)
                    ->update(['status' => Order::STATUS_DIBATALKAN]);

                if ($checkout->user_id) {
                    Notification::create([
                        'user_id' => $checkout->user_id,
                        'tipe' => Notification::TIPE_PEMBAYARAN,
                        'judul' => 'Pembayaran Kedaluwarsa',
                        'pesan' => 'Pembayaran telah melewati batas waktu dan pesanan dibatalkan otomatis. Silakan lakukan pemesanan ulang bila masih ingin berbelanja.',
                        'url' => route('customer.order-tracking'),
                    ]);
                }
            });

            $expired++;
        }

        return $expired;
    }
}