<?php

namespace App\Support;

/**
 * Katalog produk demo Raliva.
 *
 * Sumber tunggal untuk pemetaan foto lokal, normalisasi warna, dan stok demo
 * agar seeder serta command repair tidak saling berbeda.
 */
class DemoProductCatalog
{
    /**
     * Pasangan nomor aset lokal (public/assets/seeders) per nama produk.
     *
     * @var array<string, array{0: int, 1: int}>
     */
    public const IMAGE_PAIRS = [
        'Trench Coat Signature' => [1, 2],
        'Oversized Linen Shirt' => [3, 4],
        'Wide Leg Trousers' => [5, 6],
        'Midi Dress Linen' => [9, 10],
        'Knit Cardigan Rajut' => [11, 12],
        'Silk Scarf Premium' => [15, 16],
        'Basic T-Shirt Cotton' => [7, 8],
        'Relaxed Blazer' => [13, 14],
        'Pleated Skirt' => [10, 6],
        'Leather Belt' => [5, 6],
        'Straight Fit Pants' => [5, 6],
        'Pleated Midi Skirt' => [10, 6],
        'Linen Blend Shirt' => [3, 4],
        'Signature Tote Bag' => [15, 16],
        'Minimalist Cap' => [7, 8],
        'Silk Blend Dress' => [9, 10],
    ];

    /**
     * Produk yang fotonya masih memakai aset terdekat dan perlu diganti nanti.
     *
     * @var array<int, string>
     */
    public const TEMPORARY_IMAGE_PRODUCTS = [
        'Pleated Skirt',
        'Pleated Midi Skirt',
        'Signature Tote Bag',
        'Minimalist Cap',
    ];

    public const STOCK_PER_VARIANT = 20;

    public const STOCK_MINIMUM = 5;

    /**
     * Path foto lokal untuk produk demo, atau null bila bukan produk demo.
     *
     * @return array<int, string>|null
     */
    public static function imagePathsFor(string $namaProduk): ?array
    {
        $pair = self::IMAGE_PAIRS[$namaProduk] ?? null;

        if ($pair === null) {
            return null;
        }

        return array_map(fn (int $nomor) => 'assets/seeders/'.$nomor.'.jfif', $pair);
    }

    public static function isDemoProduct(string $namaProduk): bool
    {
        return array_key_exists($namaProduk, self::IMAGE_PAIRS);
    }

    public static function canonicalColor(string $warna): string
    {
        return WarnaPalet::canonicalName($warna);
    }

    public static function colorHex(string $warna): ?string
    {
        return WarnaPalet::resolve(null, self::canonicalColor($warna));
    }
}
