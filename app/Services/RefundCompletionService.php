<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\ActivityLogger;
use App\Support\CustomerWalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundCompletionService
{
    /**
     * Selesaikan refund yang sudah disetujui: potong saldo toko, tandai selesai,
     * kembalikan saldo akun customer (bila dibayar via saldo akun), dan tandai
     * pesanan full-refund sebagai 'refund'. Mengunci baris refund & wallet toko.
     *
     * @throws \RuntimeException ketika refund tidak dapat diselesaikan.
     */
    public static function complete(Refund $refund, ?string $path = null, ?string $deskripsi = null): void
    {
        $refund->loadMissing(['order.store', 'order.checkout.payment.paymentMethod', 'order.checkout.user']);

        $store = $refund->order?->store;

        if (! $store) {
            static::cleanupFile($path);
            throw new \RuntimeException('Pesanan tidak terhubung ke toko, refund tidak dapat diselesaikan.');
        }

        $lama = $refund->only(['status']);

        try {
            DB::transaction(function () use ($refund, $store, $path, $deskripsi) {
                $locked = Refund::whereKey($refund->refund_id)->lockForUpdate()->first();

                if (! $locked || $locked->status !== Refund::STATUS_DISETUJUI) {
                    throw new \RuntimeException('Status refund sudah berubah oleh pihak lain.');
                }

                $wallet = Wallet::where('store_id', $store->store_id)->lockForUpdate()->first();

                if (! $wallet) {
                    throw new \RuntimeException('Wallet toko tidak ditemukan, refund dibatalkan.');
                }

                $saldoSebelum = (float) $wallet->saldo_tersedia;

                if ($saldoSebelum < (float) $locked->jumlah) {
                    throw new \RuntimeException('Saldo toko tidak cukup untuk refund.');
                }

                $wallet->decrement('saldo_tersedia', (float) $locked->jumlah);

                WalletTransaction::create([
                    'wallet_id' => $wallet->wallet_id,
                    'refund_id' => $locked->refund_id,
                    'jenis_transaksi' => WalletTransaction::JENIS_REFUND_KELUAR,
                    'jumlah' => (float) $locked->jumlah,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldoSebelum - (float) $locked->jumlah,
                    'keterangan' => sprintf('Refund %s untuk pesanan %s.', $locked->order->nomor_order ?? '-', $locked->tipe_refund),
                ]);

                $locked->update([
                    'status' => Refund::STATUS_SELESAI,
                    'selesai_pada' => now(),
                    'file_bukti' => $path,
                    'deskripsi_bukti' => $deskripsi,
                    'bukti_diupload_pada' => $path ? now() : null,
                ]);

                $payment = $locked->order?->checkout?->payment;

                if (
                    $payment
                    && $payment->paymentMethod?->kode_metode === PaymentMethod::KODE_SALDO_AKUN
                    && $locked->order?->checkout?->user
                ) {
                    CustomerWalletService::refundToWallet(
                        $locked->order,
                        (float) $locked->jumlah,
                        sprintf('Refund %s pesanan %s dikembalikan ke saldo akun.', $locked->tipe_refund, $locked->order->nomor_order ?? '-')
                    );
                }

                static::markOrderRefundedIfFull($locked);
            });
        } catch (\Throwable $e) {
            static::cleanupFile($path);

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
    }

    /**
     * Pesanan yang di-refund penuh ditandai status 'refund' agar keluar dari
     * statistik pesanan aktif dan tidak dapat di-rating ulang.
     */
    private static function markOrderRefundedIfFull(Refund $refund): void
    {
        if ($refund->tipe_refund !== Refund::TIPE_FULL) {
            return;
        }

        if ((float) $refund->jumlah < (float) ($refund->order?->grand_total ?? 0)) {
            return;
        }

        $order = Order::whereKey($refund->order_id)->lockForUpdate()->first();

        if (! $order || ! in_array($order->status, [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI], true)) {
            return;
        }

        $lama = $order->only(['status']);

        ActivityLogger::log(
            'order.refunded',
            Order::class,
            $order->order_id,
            $lama,
            ['status' => Order::STATUS_REFUND],
            sprintf('Pesanan %s ditandai refund penuh (refund %s).', $order->nomor_order ?? '-', $refund->kode)
        );

        $order->update(['status' => Order::STATUS_REFUND]);
    }

    private static function cleanupFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}