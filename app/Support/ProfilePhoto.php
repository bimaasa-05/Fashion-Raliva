<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhoto
{
    public const DISK = 'public';

    /**
     * Simpan foto profil ke public/profil dengan prefix role dan id user.
     * Mengembalikan path relatif (mis. "profil/user-3-abc123.jpg").
     *
     * @return string
     */
    public static function store(UploadedFile $file, int $userId, string $prefix): string
    {
        $filename = $prefix.'-'.$userId.'-'.Str::random(20).'.'.$file->getClientOriginalExtension();
        Storage::disk(self::DISK)->putFileAs('profil', $file, $filename);

        return 'profil/'.$filename;
    }

    /**
     * Hapus foto lama. Mendukung dua lokasi penyimpanan:
     * - disk "public" (storage/app/public/profil/...)
     * - folder public/profil langsung.
     *
     * @return void
     */
    public static function delete(?string $path): void
    {
        if (empty($path) || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk(self::DISK)->delete($path);

        $public = public_path($path);
        if (is_file($public)) {
            @unlink($public);
        }
    }

    /**
     * Ganti foto lama dengan foto baru.
     *
     * @return string
     */
    public static function replace(UploadedFile $file, int $userId, string $prefix, ?string $oldPath): string
    {
        self::delete($oldPath);

        return self::store($file, $userId, $prefix);
    }
}