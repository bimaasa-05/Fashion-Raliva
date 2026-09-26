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