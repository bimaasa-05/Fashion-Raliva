<?php

namespace App\Support;

/**
 * Satu sumber kebenaran untuk warna + label badge status lintas role
 * (SuperAdmin, Admin, Owner, Gudang, Produksi). Customer tidak ikut.
 *
 * Skema warna:
 *   hijau  = aktif/selesai/terverifikasi/diterima/dst
 *   merah  = ditolak/nonaktif/gagal/refund/habis/dst
 *   amber  = diproses/menunggu produksi & QC/menipis/dst
 *   sky    = dikirim/dalam perjalanan/dst
 *   maroon = menunggu/baru/terjadwal/draft/dst (akcent brand)
 *   netral = status lain / fallback
 */
class StatusStyle
{
    public const PILL = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border whitespace-nowrap';

    public const CLASS_ACCENT = 'bg-gold-accent/10 text-gold-accent border-gold-accent/30';
    public const CLASS_AMBER = 'bg-amber-500/10 text-amber-600 border-amber-500/30';
    public const CLASS_SKY = 'bg-sky-500/10 text-sky-600 border-sky-500/30';
    public const CLASS_SUCCESS = 'bg-success/10 text-success border-success/20';
    public const CLASS_ERROR = 'bg-error/10 text-error border-error/20';
    public const CLASS_NEUTRAL = 'bg-surface-container-high text-on-surface-variant border-outline-variant';

    public const TEXT_ACCENT = 'text-gold-accent';
    public const TEXT_AMBER = 'text-amber-600';
    public const TEXT_SKY = 'text-sky-600';
    public const TEXT_SUCCESS = 'text-success';
    public const TEXT_ERROR = 'text-error';
    public const TEXT_NEUTRAL = 'text-on-surface-variant';

    /**
     * Status canonical -> [label, class]. Alias juga ikut terdaftar supaya
     * normalize() menghasilkan keystone yang sama seperti status asli.
     */
    protected const MAP = [
        // ---------- hijau (sukses) ----------
        'aktif' => ['label' => 'Aktif', 'class' => self::CLASS_SUCCESS],
        'aktif_berjalan' => ['label' => 'Aktif', 'class' => self::CLASS_SUCCESS],
        'berjalan' => ['label' => 'Berjalan', 'class' => self::CLASS_SUCCESS],
        'selesai' => ['label' => 'Selesai', 'class' => self::CLASS_SUCCESS],
        'terverifikasi' => ['label' => 'Terverifikasi', 'class' => self::CLASS_SUCCESS],
        'verifikasi' => ['label' => 'Perlu Verifikasi', 'class' => self::CLASS_ACCENT],
        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => self::CLASS_ACCENT],
        'diterima' => ['label' => 'Diterima', 'class' => self::CLASS_SUCCESS],
        'disetujui' => ['label' => 'Disetujui', 'class' => self::CLASS_SUCCESS],
        'approved' => ['label' => 'Disetujui', 'class' => self::CLASS_SUCCESS],
        'received' => ['label' => 'Diterima', 'class' => self::CLASS_SUCCESS],
        'diverifikasi' => ['label' => 'Diverifikasi', 'class' => self::CLASS_SUCCESS],
        'berhasil' => ['label' => 'Berhasil', 'class' => self::CLASS_SUCCESS],
        'lulus_qc' => ['label' => 'Lulus QC', 'class' => self::CLASS_SUCCESS],
        'lulus' => ['label' => 'Lulus', 'class' => self::CLASS_SUCCESS],
        'sesuai' => ['label' => 'Sesuai', 'class' => self::CLASS_SUCCESS],
        'aman' => ['label' => 'Aman', 'class' => self::CLASS_SUCCESS],
        'terkirim' => ['label' => 'Terkirim', 'class' => self::CLASS_SUCCESS],
        'tersedia' => ['label' => 'Tersedia', 'class' => self::CLASS_SUCCESS],
        'sukses' => ['label' => 'Sukses', 'class' => self::CLASS_SUCCESS],
        'bayar' => ['label' => 'Bayar', 'class' => self::CLASS_SUCCESS],
        'terbayar' => ['label' => 'Terbayar', 'class' => self::CLASS_SUCCESS],
        'ok' => ['label' => 'OK', 'class' => self::CLASS_SUCCESS],
        'done' => ['label' => 'Selesai', 'class' => self::CLASS_SUCCESS],

        // ---------- amber (proses) ----------
        'dibayar' => ['label' => 'Dibayar', 'class' => self::CLASS_AMBER],
        'diproses' => ['label' => 'Diproses', 'class' => self::CLASS_AMBER],
        'proses' => ['label' => 'Diproses', 'class' => self::CLASS_AMBER],
        'sedang_diproses' => ['label' => 'Diproses', 'class' => self::CLASS_AMBER],
        'in_progress' => ['label' => 'Diproses', 'class' => self::CLASS_AMBER],
        'processing' => ['label' => 'Diproses', 'class' => self::CLASS_AMBER],
        'produksi' => ['label' => 'Produksi', 'class' => self::CLASS_AMBER],
        'menunggu_produksi' => ['label' => 'Menunggu Produksi', 'class' => self::CLASS_AMBER],
        'menunggu_qc' => ['label' => 'Menunggu QC', 'class' => self::CLASS_AMBER],
        'ditangani' => ['label' => 'Ditangani', 'class' => self::CLASS_AMBER],
        'menipis' => ['label' => 'Menipis', 'class' => self::CLASS_AMBER],
        'free' => ['label' => 'Free', 'class' => self::CLASS_AMBER],
        'gratis' => ['label' => 'Gratis', 'class' => self::CLASS_AMBER],

        // ---------- sky (pengiriman) ----------
        'dikirim' => ['label' => 'Dikirim', 'class' => self::CLASS_SKY],
        'dalam_perjalanan' => ['label' => 'Dalam Perjalanan', 'class' => self::CLASS_SKY],
        'perjalanan' => ['label' => 'Perjalanan', 'class' => self::CLASS_SKY],
        'in_transit' => ['label' => 'Dalam Perjalanan', 'class' => self::CLASS_SKY],
        'transit' => ['label' => 'Transit', 'class' => self::CLASS_SKY],
        'ekspedisi' => ['label' => 'Ekspedisi', 'class' => self::CLASS_SKY],
        'shipping' => ['label' => 'Dikirim', 'class' => self::CLASS_SKY],

        // ---------- maroon (menunggu / akcent) ----------
        'pending' => ['label' => 'Pending', 'class' => self::CLASS_ACCENT],
        'pending_payment' => ['label' => 'Menunggu Pembayaran', 'class' => self::CLASS_ACCENT],
        'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'class' => self::CLASS_ACCENT],
        'menunggu' => ['label' => 'Menunggu', 'class' => self::CLASS_ACCENT],
        'baru' => ['label' => 'Baru', 'class' => self::CLASS_ACCENT],
        'terbuka' => ['label' => 'Terbuka', 'class' => self::CLASS_ACCENT],
        'open' => ['label' => 'Terbuka', 'class' => self::CLASS_ACCENT],
        'terjadwal' => ['label' => 'Terjadwal', 'class' => self::CLASS_ACCENT],
        'ditunda' => ['label' => 'Ditunda', 'class' => self::CLASS_ACCENT],
        'draft' => ['label' => 'Draft', 'class' => self::CLASS_ACCENT],
        'requested' => ['label' => 'Diminta', 'class' => self::CLASS_ACCENT],
        'dilaporkan' => ['label' => 'Dilaporkan', 'class' => self::CLASS_ACCENT],
        'menunggu_pemeriksaan' => ['label' => 'Menunggu Pemeriksaan', 'class' => self::CLASS_ACCENT],
        'menunggu_resi' => ['label' => 'Menunggu Resi', 'class' => self::CLASS_ACCENT],
        'menunggu_accept' => ['label' => 'Menunggu Accept', 'class' => self::CLASS_ACCENT],
        'siap_kirim' => ['label' => 'Siap Kirim', 'class' => self::CLASS_ACCENT],
        'eskalasi' => ['label' => 'Eskalasi', 'class' => self::CLASS_ACCENT],
        'escalated' => ['label' => 'Eskalasi', 'class' => self::CLASS_ACCENT],
        'review' => ['label' => 'Review', 'class' => self::CLASS_ACCENT],
        'menunggu_tinjauan' => ['label' => 'Menunggu Tinjauan', 'class' => self::CLASS_ACCENT],
        'tinjauan' => ['label' => 'Tinjauan', 'class' => self::CLASS_ACCENT],

        // ---------- merah (gagal) ----------
        'ditolak' => ['label' => 'Ditolak', 'class' => self::CLASS_ERROR],
        'gagal' => ['label' => 'Gagal', 'class' => self::CLASS_ERROR],
        'gagal_qc' => ['label' => 'Gagal QC', 'class' => self::CLASS_ERROR],
        'nonaktif' => ['label' => 'Nonaktif', 'class' => self::CLASS_ERROR],
        'non_aktif' => ['label' => 'Nonaktif', 'class' => self::CLASS_ERROR],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => self::CLASS_ERROR],
        'refund' => ['label' => 'Refund', 'class' => self::CLASS_ERROR],
        'habis' => ['label' => 'Habis', 'class' => self::CLASS_ERROR],
        'kritis' => ['label' => 'Kritis', 'class' => self::CLASS_ERROR],
        'selisih' => ['label' => 'Selisih', 'class' => self::CLASS_ERROR],
        'kadaluarsa' => ['label' => 'Kadaluarsa', 'class' => self::CLASS_ERROR],
        'expired' => ['label' => 'Kadaluarsa', 'class' => self::CLASS_ERROR],
        'suspend' => ['label' => 'Suspend', 'class' => self::CLASS_ERROR],
        'berhenti' => ['label' => 'Berhenti', 'class' => self::CLASS_ERROR],
        'penuh' => ['label' => 'Penuh', 'class' => self::CLASS_ERROR],
        'cancelled' => ['label' => 'Dibatalkan', 'class' => self::CLASS_ERROR],
        'canceled' => ['label' => 'Dibatalkan', 'class' => self::CLASS_ERROR],
        'reject' => ['label' => 'Ditolak', 'class' => self::CLASS_ERROR],

        // ---------- netral ----------
        'ditutup' => ['label' => 'Ditutup', 'class' => self::CLASS_NEUTRAL],
        'netral' => ['label' => 'Netral', 'class' => self::CLASS_NEUTRAL],
        'arsip' => ['label' => 'Arsip', 'class' => self::CLASS_NEUTRAL],
        'none' => ['label' => '-', 'class' => self::CLASS_NEUTRAL],
        '-' => ['label' => '-', 'class' => self::CLASS_NEUTRAL],

        // ---------- prioritas ----------
        'rendah' => ['label' => 'Rendah', 'class' => self::CLASS_NEUTRAL],
        'normal' => ['label' => 'Normal', 'class' => self::CLASS_SUCCESS],
        'tinggi' => ['label' => 'Tinggi', 'class' => self::CLASS_AMBER],
        'urgent' => ['label' => 'Urgent', 'class' => self::CLASS_ERROR],
    ];

    /** normalisasi key: huruf kecil, spasi/strip -> underscore */
    public static function normalize(string $status): string
    {
        $key = mb_strtolower(trim($status));
        $key = str_replace([' ', '-', '.', '/'], '_', $key);
        $key = preg_replace('/_+/', '_', $key) ?? $key;

        return trim($key, '_');
    }

    /** badge info: [label, class] untuk status apa pun (fallback netral) */
    public static function badge(string $status, ?string $label = null): array
    {
        $key = static::normalize($status);
        $item = static::MAP[$key] ?? ['label' => static::labelFromKey($key), 'class' => self::CLASS_NEUTRAL];

        return [
            'label' => $label ?: $item['label'],
            'class' => $item['class'],
        ];
    }

    /** class badge saja */
    public static function badgeClass(string $status): string
    {
        return static::badge($status)['class'];
    }

    /** class teks saja (untuk angka/kolom tanpa pill) */
    public static function textClass(string $status): string
    {
        return match (static::badge($status)['class']) {
            self::CLASS_SUCCESS => self::TEXT_SUCCESS,
            self::CLASS_AMBER => self::TEXT_AMBER,
            self::CLASS_SKY => self::TEXT_SKY,
            self::CLASS_ACCENT => self::TEXT_ACCENT,
            self::CLASS_ERROR => self::TEXT_ERROR,
            default => self::TEXT_NEUTRAL,
        };
    }

    /** label saja */
    public static function label(string $status, ?string $label = null): string
    {
        return static::badge($status, $label)['label'];
    }

    /** map lengkap untuk JS (semua key yang dikenal) */
    public static function toJson(): array
    {
        return static::MAP;
    }

    protected static function labelFromKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }
}