<?php

namespace App\Support;

/**
 * Format angka yang konsisten untuk seluruh tampilan SuperAdmin.
 *
 * Angka jumlah memakai pemisah ribuan Indonesia, sedangkan nominal besar
 * diringkas agar tidak memecah kartu: Rp 1,2 M / Rp 24,8 jt / Rp 425 rb.
 */
final class NumberDisplay
{
    public const NBSP = "\u{00A0}";

    public static function count(int|float|string|null $value): string
    {
        return number_format((float) ($value ?? 0), 0, ',', '.');
    }

    public static function decimal(int|float|string|null $value, int $decimals = 1): string
    {
        return number_format((float) ($value ?? 0), $decimals, ',', '.');
    }

    public static function rupiah(int|float|string|null $value): string
    {
        $number = (float) ($value ?? 0);
        $sign = $number < 0 ? '-' : '';

        return $sign.'Rp'.self::NBSP.self::count(abs($number));
    }

    public static function compactRupiah(int|float|string|null $value): string
    {
        $number = (float) ($value ?? 0);
        $sign = $number < 0 ? '-' : '';
        $absolute = abs($number);

        [$divisor, $suffix, $decimals] = match (true) {
            $absolute >= 1_000_000_000_000 => [1_000_000_000_000, ' T', 1],
            $absolute >= 1_000_000_000 => [1_000_000_000, ' M', 1],
            $absolute >= 1_000_000 => [1_000_000, ' jt', 1],
            $absolute >= 10_000 => [1_000, ' rb', 0],
            default => [1, '', 0],
        };

        if ($divisor === 1) {
            return self::rupiah($number);
        }

        $formatted = number_format($absolute / $divisor, $decimals, ',', '.');

        if ($decimals > 0) {
            $formatted = rtrim(rtrim($formatted, '0'), ',');
        }

        return $sign.'Rp'.self::NBSP.$formatted.$suffix;
    }

    public static function percent(int|float|string|null $value, int $decimals = 1): string
    {
        return self::decimal($value, $decimals).'%';
    }
}
