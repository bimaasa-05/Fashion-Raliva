<?php

if (! function_exists('photo_url')) {
    /**
     * URL publik untuk file yang diunggah (produk, logo toko, iklan, dll).
     * Bersifat 3-in-1: URL absolut dipakai apa adanya, path public assets/
     * ditautkan ke root, dan file storage ditautkan lewat symlink /storage.
     */
    function photo_url($path)
    {
        if (! $path) {
            return '';
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }
}

if (! function_exists('product_photo_url')) {
    /**
     * URL foto produk dengan fallback aset lokal.
     *
     * Path kosong atau URL picsum lama tidak lagi dipakai agar halaman tidak
     * bergantung pada layanan gambar eksternal.
     */
    function product_photo_url($path = null, $seed = null): string
    {
        if ($path && ! str_contains((string) $path, 'picsum.photos')) {
            return photo_url($path);
        }

        if ($seed !== null && $seed !== '') {
            $index = is_numeric($seed)
                ? ((abs((int) $seed) - 1) % 16 + 16) % 16 + 1
                : abs(crc32((string) $seed)) % 16 + 1;
        } else {
            $index = 1;
        }

        return asset('assets/seeders/'.$index.'.jfif');
    }
}