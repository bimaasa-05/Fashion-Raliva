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

    /**
     * @param mixed $names
     * @param mixed $hexes
     * @return array{names: string[], hexes: string[]}
     */
    public static function normalizeSubmissionOrFail(mixed $names, mixed $hexes): array
    {
        if (! is_array($names) || $names === []) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'warna' => 'Pilih minimal 1 warna.',
            ]);
        }

        $hexes = is_array($hexes) ? array_values($hexes) : [];
        $cleanNames = [];
        $cleanHexes = [];

        foreach (array_values($names) as $i => $rawName) {
            $name = trim((string) $rawName);
            $key = "warna.{$i}";

            if ($name === '' || mb_strlen($name) < 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $key => 'Nama warna wajib diisi minimal 2 karakter.',
                ]);
            }
            if (preg_match('/^warna\s*\d+$/i', $name)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $key => 'Berikan nama warna yang bermakna, misalnya Tosca.',
                ]);
            }

            $canonical = self::canonicalName($name);
            $lowerNames = array_map(fn ($v) => mb_strtolower($v), $cleanNames);
            if (in_array(mb_strtolower($canonical), $lowerNames, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $key => "Warna \"{$canonical}\" sudah dipilih.",
                ]);
            }

            $hex = self::resolve($hexes[$i] ?? '', $canonical);
            $known = array_key_exists($canonical, self::ALL);
            if (! $known && ! $hex) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $key => "Warna custom \"{$name}\" wajib memiliki kode hex.",
                ]);
            }

            $cleanNames[] = $canonical;
            $cleanHexes[] = $hex ?? '';
        }

        return ['names' => $cleanNames, 'hexes' => $cleanHexes];
    }

    public static function canonicalName(string $name): string
    {
        $key = mb_strtolower(trim($name));

        foreach (self::ALL as $label => $hex) {
            if (mb_strtolower($label) === $key) {
                return $label;
            }
        }

        return trim($name);
    }

    public static function resolve(?string $storedHex, ?string $name): ?string
    {
        $storedHex = trim((string) $storedHex);
        if (preg_match('/^#[0-9a-fA-F]{6}$/', $storedHex)) {
            return '#'.strtolower(ltrim($storedHex, '#'));
        }

        $key = mb_strtolower(trim((string) $name));
        if ($key === '') {
            return null;
        }

        foreach (self::ALL as $label => $hex) {
            if (mb_strtolower($label) === $key) {
                return $hex;
            }
        }

        return null;
    }
}