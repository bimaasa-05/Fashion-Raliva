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

if (! function_exists('brand_favicon_url')) {
    /**
     * URL favicon platform. Sumber: Setting "favicon_platform" yang di-upload
     * Super Admin di Pengaturan Sistem (Fase 6). Selama belum ada unggahan,
     * fallback ke /favicon.ico bawaan public.
     */
    function brand_favicon_url(): string
    {
        try {
            $file = trim((string) Setting::get(Setting::FAVICON_PLATFORM));
        } catch (\Throwable) {
            $file = '';
        }

        if ($file !== '') {
            $url = photo_url($file);
            $path = public_path('storage/'.$file);

            if (is_file($path)) {
                $url .= (str_contains($url, '?') ? '&' : '?').'v='.filemtime($path);
            }

            return $url;
        }

        return asset('favicon.ico');
    }
}

if (! function_exists('brand_logo_url')) {
    /**
     * URL logo platform (wordmark). Sumber: Setting "logo_platform" yang di-upload
     * Super Admin di Pengaturan Sistem (Fase 6). String kosong berarti belum ada
     * unggahan: wordmark tampil sebagai teks nama platform (partial brand-content).
     */
    function brand_logo_url(): string
    {
        try {
            $file = trim((string) Setting::get(Setting::LOGO_PLATFORM));
        } catch (\Throwable) {
            $file = '';
        }

        if ($file !== '') {
            $url = photo_url($file);
            $path = public_path('storage/'.$file);

            if (is_file($path)) {
                $url .= (str_contains($url, '?') ? '&' : '?').'v='.filemtime($path);
            }

            return $url;
        }

        return '';
    }
}
