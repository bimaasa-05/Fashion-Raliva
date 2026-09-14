<?php

namespace App\Support;

use App\Models\Role;
use App\Models\Setting;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreGate
{
    /**
     * Role yang terikat ke suatu toko dan terkunci saat toko ditangguhkan.
     */
    private const STAFF_ROLES = [
        Role::ADMIN,
        Role::GUDANG,
        Role::PRODUKSI,
    ];

    /**
     * Apakah akun saat ini terkunci karena tokonya ditangguhkan (status nonaktif)?
     * Owner: toko miliknya berstatus nonaktif.
     * Admin/Gudang/Produksi: salah satu store_staff aktif menunjuk toko nonaktif.
     */
    public static function isLocked(?User $user = null): bool
    {
        $user ??= Auth::user();

        if (! $user) {
            return false;
        }

        $storeIds = static::storeIdsFor($user);

        if ($storeIds === []) {
            return false;
        }

        return Store::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', Store::STATUS_NONAKTIF)
            ->exists();
    }

    /**
     * Nama toko yang sedang ditangguhkan (untuk banner). Mengembalikan daftar nama.
     *
     * @return string[]
     */
    public static function suspendedStoreNames(?User $user = null): array
    {
        $user ??= Auth::user();

        if (! $user) {
            return [];
        }

        $storeIds = static::storeIdsFor($user);

        if ($storeIds === []) {
            return [];
        }

        return Store::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', Store::STATUS_NONAKTIF)
            ->pluck('nama_toko')
            ->all();
    }

    /**
     * Model toko yang sedang ditangguhkan (untuk banner), termasuk batas waktu bila ada.
     *
     * @return Store[]
     */
    public static function suspendedStores(?User $user = null): array
    {
        $user ??= Auth::user();

        if (! $user) {
            return [];
        }

        $storeIds = static::storeIdsFor($user);

        if ($storeIds === []) {
            return [];
        }

        return Store::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', Store::STATUS_NONAKTIF)
            ->get(['store_id', 'nama_toko', 'ditangguhkan_sampai'])
            ->all();
    }

    /**
     * Nomor WhatsApp dukungan platform (untuk wa.me), normalisasi ke digit saja.
     */
    public static function supportWhatsapp(): ?string
    {
        $wa = Setting::get(Setting::WHATSAPP_SUPPORT, '');

        if ($wa === null || $wa === '') {
            return null;
        }

        $wa = preg_replace('/\D+/', '', $wa);

        return $wa !== '' ? $wa : null;
    }

    /**
     * Store id yang terkait dengan akun berdasar role-nya.
     *
     * @return int[]
     */
    private static function storeIdsFor(User $user): array
    {
        $role = $user->role?->nama_role;

        if ($role === Role::OWNER) {
            return OwnerContext::ownedStoreIds($user);
        }

        if (in_array($role, self::STAFF_ROLES, true)) {
            return StoreStaff::query()
                ->where('user_id', $user->user_id)
                ->where('status', 'aktif')
                ->pluck('store_id')
                ->all();
        }

        return [];
    }
}