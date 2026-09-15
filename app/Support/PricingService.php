<?php

namespace App\Support;

use App\Models\Setting;

class PricingService
{
    /**
     * Persentase PPN dari Setting platform.
     */
    public static function taxPersen(): float
    {
        return (float) Setting::get(Setting::PAJAK_PERSEN, '11');
    }

    /**
     * Biaya layanan tetap per transaksi dari Setting platform (default 0).
     */
    public static function serviceFee(): float
    {
        return (float) Setting::get(Setting::BIAYA_LAYANAN, '0');
    }

    /**
     * Hitung pajak (PPN) dari subtotal — basis harga barang saja.
     */
    public static function taxFor(float $subtotal): int
    {
        $persen = self::taxPersen();

        return $persen > 0 ? (int) round($subtotal * $persen / 100) : 0;
    }

    /**
     * Hitung total pajak + biaya layanan + grand total.
     *
     * @return array{0: int, 1: int, 2: int} [total_pajak, biaya_layanan, grand_total]
     */
    public static function computeTotals(float $subtotal, float $shipping): array
    {
        $tribut = self::taxFor($subtotal);
        $biaya = (int) round(self::serviceFee());
        $grand = (int) round($subtotal) + (int) round($shipping) + $tribut + $biaya;

        return [$tribut, $biaya, $grand];
    }
}