<?php

namespace App\Support;

/**
 * Palet warna produk (preset pilihan Admin).
 * Satu sumber kebenaran untuk Admin (kisi swatch) dan Customer (detail produk).
 */
class WarnaPalet
{
    public const ALL = [
        'Hitam' => '#1c1b1b',
        'Krem' => '#e8dcc8',
        'Navy' => '#22304a',
        'Camel' => '#c19a6b',
        'Putih' => '#f5f3f3',
        'Merah' => '#c62828',
        'Biru' => '#2360a8',
        'Kuning' => '#e6b91e',
        'Marun' => '#7d2b33',
        'Hijau' => '#2e7d32',
        'Abu-abu' => '#7c7c7c',
        'Cokelat' => '#6d4c41',
        'Pink' => '#e29bb0',
        'Oranye' => '#e8792f',
        'Ungu' => '#6a4c93',
        'Tosca' => '#2f9e94',
        'Lilac' => '#b09cc1',
        'Gold' => '#c9a24d',
        'Silver' => '#b9bdc4',
        'Mint' => '#a8d5ba',
        'Beige' => '#d7c9a8',
        'Burgundy' => '#6e1423',
        'Emerald' => '#046e4c',
        'Coral' => '#f2875c',
        'Teal' => '#0f766e',
        'Cream' => '#f6ecd9',
        'Mustard' => '#d2a13c',
        'Olive' => '#708238',
        'Rust' => '#b7410e',
        'Violet' => '#7c3aed',
        'Sage' => '#9caf88',
    ];

    public static function all(): array
    {
        return self::ALL;
    }

    public static function hex(string $name): ?string
    {
        $name = trim($name);

        return self::ALL[$name] ?? null;
    }
}