<?php

namespace App\Support;

use App\Models\StoreDocument;

class DokumenLegal
{
    public const JENIS_KTP = 'ktp';

    public const JENIS_SIU = 'siu'; // NIB tampil sebagai "Surat Izin Usaha (NIB)".

    public const JENIS_NPWP = 'npwp';

    public const JENIS = [
        self::JENIS_KTP,
        self::JENIS_SIU,
        self::JENIS_NPWP,
    ];

    public const LABEL = [
        self::JENIS_KTP => 'KTP',
        self::JENIS_SIU => 'NIB',
        self::JENIS_NPWP => 'NPWP',
    ];

    public const STATUS_TERVERIFIKASI = 'terverifikasi';

    /**
     * Jenis legal yang sudah terverifikasi untuk toko (ktp / siu / npwp).
     *
     * @return string[]
     */
    public static function jenisTerverifikasi(int $storeId): array
    {
        return StoreDocument::where('store_id', $storeId)
            ->whereIn('jenis', static::JENIS)
            ->where('status', static::STATUS_TERVERIFIKASI)
            ->pluck('jenis')
            ->all();
    }

    /**
     * Syarat wajib: minimal SATU dari KTP / NIB / NPWP sudah terverifikasi.
     */
    public static function satisfied(int $storeId): bool
    {
        return static::jenisTerverifikasi($storeId) !== [];
    }

    public static function namaTersedia(int $storeId): string
    {
        return implode(', ', array_map(
            fn ($j) => static::LABEL[$j] ?? $j,
            static::jenisTerverifikasi($storeId)
        ));
    }

    public static function pesanKurang(int $storeId): string
    {
        return 'Pembelian slot wajib melampirkan identitas usaha: minimal salah satu dari KTP, NIB, atau NPWP sudah terverifikasi. '
            .'Lengkapi dokumen di Pengajuan Toko terlebih dahulu.';
    }
}
