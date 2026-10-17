<?php

use App\Models\Setting;

if (! function_exists('brand')) {
    /**
     * Nama platform yang tampil di seluruh UI (sidebar, judul tab, invoice, dll).
     *
     * Sumber utama: Setting "nama_platform" yang diatur Super Admin di
     * halaman Pengaturan Sistem. Fallback: config('app.name') (APP_NAME di .env).
     * Hasil di-cache per request; saat unit test cache dilewati agar perubahan
     * setting antar-tes langsung terbaca.
     */
    function brand(): string
    {
        static $cached = null;

        if ($cached !== null && ! app()->runningUnitTests()) {
            return $cached;
        }

        try {
            $nama = trim((string) Setting::get(Setting::NAMA_PLATFORM));
        } catch (\Throwable) {
            $nama = '';
        }

        return $cached = $nama !== '' ? $nama : (string) config('app.name', 'Raliva');
    }
}
