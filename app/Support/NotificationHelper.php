<?php

namespace App\Support;

use App\Models\Notification;
use App\Services\NotificationService;

if (! function_exists('App\Support\notify')) {
    /**
     * Buat notifikasi sekali baris.
     *
     * @param  string  $tipe  Salah satu konstanta Notification::TIPE_*
     */
    function notify(
        int $penerimaId,
        string $tipe,
        string $judul,
        string $pesan,
        ?int $aktorId = null,
        ?string $url = null,
    ): Notification {
        return NotificationService::fire($penerimaId, $tipe, $judul, $pesan, $aktorId, $url);
    }
}

if (! function_exists('App\Support\notifyRole')) {
    /**
     * Kirim notifikasi ke semua user aktif dengan peran tertentu.
     */
    function notifyRole(
        string $roleName,
        string $tipe,
        string $judul,
        string $pesan,
        ?int $aktorId = null,
        ?string $url = null,
    ): int {
        return NotificationService::sendToRole($roleName, $tipe, $judul, $pesan, $aktorId, $url);
    }
}