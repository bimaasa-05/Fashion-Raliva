<?php

namespace App\Support;

use App\Models\Commission;
use App\Models\Order;
use App\Models\Setting;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Kredit dana penjualan ke saldo tersedia owner ketika order selesai.
     *
     * Idempotent: jika komisi untuk order ini sudah ada (aktif), tidak diproses lagi.
     * Komisi platform dipotong dari grand_total dan dicatat pada record Commission,
     * wallet bertambah sebesar neto (grand_total - komisi).
     */
    public static function creditOrder(Order $order): void
    {
        if ($order->status !== Order::STATUS_SELESAI) {
            return;
        }

        if (Commission::where('order_id', $order->order_id)
            ->where('status', Commission::STATUS_AKTIF)
            ->exists()) {
            return;
        }

        $persen = (float) Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5');
        $persen = min(100, max(0, $persen));

        $grand = (float) $order->grand_total;
        $komisi = round($grand * $persen / 100);
        $neto = $grand - $komisi;

        DB::transaction(function () use ($order, $persen, $grand, $komisi, $neto) {
            if (Commission::where('order_id', $order->order_id)
                ->where('status', Commission::STATUS_AKTIF)
                ->exists()) {
                return;
            }

            $wallet = $order->store()->firstOrFail()->wallet()->lockForUpdate()->first();

            if (! $wallet) {
                \App\Models\Wallet::create([
                    'store_id' => $order->store_id,
                    'saldo_tersedia' => 0,
                    'saldo_tertahan' => 0,
                ]);
                $wallet = $order->store()->firstOrFail()->wallet()->lockForUpdate()->first();
            }

            $sebelum = (float) $wallet->saldo_tersedia;
            $wallet->increment('saldo_tersedia', $neto);
            $sesudah = (float) $wallet->fresh()->saldo_tersedia;

            $commission = Commission::create([
                'order_id' => $order->order_id,
                'store_id' => $order->store_id,
                'persentase' => $persen,
                'dasar_perhitungan' => $grand,
                'jumlah_komisi' => $komisi,
                'status' => Commission::STATUS_AKTIF,
            ]);

            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'order_id' => $order->order_id,
                'commission_id' => $commission->commission_id,
                'jenis_transaksi' => WalletTransaction::JENIS_PENJUALAN_MASUK,
                'jumlah' => $neto,
                'saldo_sebelum' => $sebelum,
                'saldo_sesudah' => $sesudah,
                'keterangan' => sprintf(
                    'Penjualan %s masuk saldo tersedia (neto setelah komisi platform %s).',
                    $order->nomor_order,
                    rtrim(rtrim(number_format($persen, 2, ',', '.'), '0'), ',')
                ),
            ]);
        });
    }
}