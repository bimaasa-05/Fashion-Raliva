<?php

namespace App\Support;

use App\Models\Notification;

class NotificationMeta
{
    /**
     * Peta tipe notifikasi -> ikon, label, dan tone (konsisten lintas role).
     */
    protected const MAP = [
        Notification::TIPE_ORDER => ['icon' => 'shopping_bag', 'label' => 'Pesanan', 'tone' => 'info'],
        Notification::TIPE_PEMBAYARAN => ['icon' => 'payments', 'label' => 'Pembayaran', 'tone' => 'success'],
        Notification::TIPE_PENGIRIMAN => ['icon' => 'local_shipping', 'label' => 'Pengiriman', 'tone' => 'info'],
        Notification::TIPE_KOMPLAIN => ['icon' => 'support_agent', 'label' => 'Komplain', 'tone' => 'warning'],
        Notification::TIPE_ULASAN => ['icon' => 'star', 'label' => 'Ulasan', 'tone' => 'info'],
        Notification::TIPE_WALLET => ['icon' => 'account_balance_wallet', 'label' => 'Keuangan', 'tone' => 'success'],
        Notification::TIPE_PROMO => ['icon' => 'local_offer', 'label' => 'Promo', 'tone' => 'warning'],
        Notification::TIPE_SISTEM => ['icon' => 'notifications', 'label' => 'Sistem', 'tone' => 'neutral'],
        // Tipe khusus gudang.
        'stok_menipis' => ['icon' => 'warning', 'label' => 'Stok Menipis', 'tone' => 'warning'],
        'stok_habis' => ['icon' => 'block', 'label' => 'Stok Habis', 'tone' => 'error'],
        'barang_masuk' => ['icon' => 'inventory_2', 'label' => 'Barang Masuk', 'tone' => 'success'],
        'barang_keluar' => ['icon' => 'unarchive', 'label' => 'Barang Keluar', 'tone' => 'info'],
        'pemenuhan' => ['icon' => 'check_circle', 'label' => 'Pemenuhan', 'tone' => 'success'],
        'pemeriksaan' => ['icon' => 'fact_check', 'label' => 'Pemeriksaan', 'tone' => 'info'],
        'pemindahan' => ['icon' => 'swap_horiz', 'label' => 'Pemindahan', 'tone' => 'info'],
    ];

    protected const TONE_CLASS = [
        'success' => 'bg-secondary-container/20 text-secondary',
        'warning' => 'bg-tertiary-container/20 text-tertiary',
        'error' => 'bg-error/10 text-error',
        'info' => 'bg-gold-accent/10 text-gold-accent',
        'neutral' => 'bg-surface-container-high text-on-surface-variant',
    ];

    public static function for(string $tipe): array
    {
        return static::MAP[$tipe] ?? ['icon' => 'notifications', 'label' => 'Sistem', 'tone' => 'neutral'];
    }

    public static function icon(string $tipe): string
    {
        return static::for($tipe)['icon'];
    }

    public static function label(string $tipe): string
    {
        return static::for($tipe)['label'];
    }

    public static function toneClass(string $tipe): string
    {
        $tone = static::for($tipe)['tone'];

        return static::TONE_CLASS[$tone] ?? static::TONE_CLASS['neutral'];
    }
}