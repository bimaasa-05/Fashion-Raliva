<?php

namespace App\Support;

use App\Models\Role;

/**
 * Pemetaan area URL → cookie sesi, dipakai hanya saat SESSION_MULTI_ROLE aktif.
 *
 * Tujuan: memisahkan sesi per area role (superadmin/owner/admin/gudang/produksi)
 * sehingga beberapa role bisa login bersamaan di satu browser (mode dev).
 * Area Customer/publik tetap memakai cookie sesi default.
 */
class SessionArea
{
    public const SUPERADMIN = 'superadmin';

    public const OWNER = 'owner';

    public const ADMIN = 'admin';

    public const GUDANG = 'gudang';

    public const PRODUKSI = 'produksi';

    /** Peta area → nama role. */
    private const AREA_ROLE = [
        self::SUPERADMIN => Role::SUPER_ADMIN,
        self::OWNER => Role::OWNER,
        self::ADMIN => Role::ADMIN,
        self::GUDANG => Role::GUDANG,
        self::PRODUKSI => Role::PRODUKSI,
    ];

    /**
     * @return string[]
     */
    public static function areas(): array
    {
        return array_keys(self::AREA_ROLE);
    }

    public static function roleForArea(string $area): ?string
    {
        return self::AREA_ROLE[$area] ?? null;
    }

    public static function areaForRole(?string $role): ?string
    {
        if ($role === null) {
            return null;
        }

        $area = array_search($role, self::AREA_ROLE, true);

        return $area === false ? null : $area;
    }

    /**
     * Area berdasarkan segmen pertama path, mis. "/owner/profil" → "owner".
     */
    public static function areaForPath(string $path): ?string
    {
        $segment = explode('/', trim($path, '/'))[0] ?? '';

        return in_array($segment, self::areas(), true) ? $segment : null;
    }

    /**
     * Area dari header Referer (untuk endpoint lintas-area seperti /notifikasi/*).
     */
    public static function areaForReferer(?string $referer): ?string
    {
        if (! $referer) {
            return null;
        }

        return self::areaForPath((string) parse_url($referer, PHP_URL_PATH));
    }

    public static function cookieNameForArea(string $area): string
    {
        return 'raliva_'.$area.'_session';
    }

    public static function isEnabled(): bool
    {
        return (bool) config('session.multi_role', false);
    }
}
