<?php

namespace App\Support;

use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\CustomerTopup;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerWalletService
{
    public static function walletFor(User $user): CustomerWallet
    {
        return CustomerWallet::firstOrCreate(
            ['user_id' => $user->user_id],
            ['saldo_tersedia' => 0]
        );
    }

    public static function balance(User $user): float
    {
        return (float) (static::walletFor($user)->saldo_tersedia ?? 0);
    }

    public static function creditTopup(User $user, CustomerTopup $topup): CustomerWallet
    {
        return DB::transaction(function () use ($user, $topup) {
            $wallet = static::walletFor($user);
            $wallet = CustomerWallet::whereKey($wallet->customer_wallet_id)->lockForUpdate()->first();

            $sebelum = (float) $wallet->saldo_tersedia;
            $wallet->increment('saldo_tersedia', $topup->jumlah);

            CustomerWalletTransaction::create([
                'customer_wallet_id' => $wallet->customer_wallet_id,
                'customer_topup_id' => $topup->customer_topup_id,
                'jenis_transaksi' => CustomerWalletTransaction::JENIS_TOPUP,
                'jumlah' => $topup->jumlah,
                'saldo_sebelum' => $sebelum,
                'saldo_sesudah' => (float) $wallet->saldo_tersedia,
                'keterangan' => 'Topup saldo akun Rp '.number_format((float) $topup->jumlah, 0, ',', '.').' (#'.$topup->customer_topup_id.')',
            ]);

            return $wallet;
        });
    }

    public static function debitForOrder(User $user, Order $order, float $jumlah): CustomerWallet
    {
        return DB::transaction(function () use ($user, $order, $jumlah) {
            $wallet = static::walletFor($user);
            $wallet = CustomerWallet::whereKey($wallet->customer_wallet_id)->lockForUpdate()->first();

            if ((float) $wallet->saldo_tersedia < $jumlah) {
                throw new \RuntimeException('Saldo akun tidak mencukupi untuk pembayaran ini.');
            }

            $sebelum = (float) $wallet->saldo_tersedia;
            $wallet->decrement('saldo_tersedia', $jumlah);

            CustomerWalletTransaction::create([
                'customer_wallet_id' => $wallet->customer_wallet_id,
                'order_id' => $order->order_id,
                'jenis_transaksi' => CustomerWalletTransaction::JENIS_PEMBAYARAN_KELUAR,
                'jumlah' => $jumlah,
                'saldo_sebelum' => $sebelum,
                'saldo_sesudah' => (float) $wallet->saldo_tersedia,
                'keterangan' => 'Pembayaran pesanan '.$order->nomor_order.' pakai saldo akun',
            ]);

            return $wallet;
        });
    }

    public static function refundToWallet(Order $order, float $jumlah, string $keterangan): CustomerWallet
    {
        return DB::transaction(function () use ($order, $jumlah, $keterangan) {
            if (CustomerWalletTransaction::where('order_id', $order->order_id)
                ->where('jenis_transaksi', CustomerWalletTransaction::JENIS_REFUND_MASUK)
                ->exists()) {
                throw new \RuntimeException('Refund untuk pesanan ini sudah pernah dikembalikan ke saldo.');
            }

            $wallet = static::walletFor($order->checkout->user);
            $wallet = CustomerWallet::whereKey($wallet->customer_wallet_id)->lockForUpdate()->first();

            $sebelum = (float) $wallet->saldo_tersedia;
            $wallet->increment('saldo_tersedia', $jumlah);

            CustomerWalletTransaction::create([
                'customer_wallet_id' => $wallet->customer_wallet_id,
                'order_id' => $order->order_id,
                'jenis_transaksi' => CustomerWalletTransaction::JENIS_REFUND_MASUK,
                'jumlah' => $jumlah,
                'saldo_sebelum' => $sebelum,
                'saldo_sesudah' => (float) $wallet->saldo_tersedia,
                'keterangan' => $keterangan,
            ]);

            return $wallet;
        });
    }
}