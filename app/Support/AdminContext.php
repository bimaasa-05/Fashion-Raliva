<?php

namespace App\Support;

use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminContext
{
    public static function currentAdmin(): ?User
    {
        $user = Auth::user();

        if ($user && $user->role?->nama_role === Role::ADMIN) {
            return $user;
        }

        return static::fallbackAdmin();
    }

    public static function fallbackAdmin(): ?User
    {
        // Fail-closed: JANGAN menebak admin lain. Mengembalikan admin pertama
        // membuat user yang salah melihat data toko admin tersebut (bocor).
        // Pemanggil yang butuh admin wajib memastikan user login role Admin.
        return null;
    }

    /**
     * @return int[]
     */
    public static function assignedStoreIds(?User $admin = null): array
    {
        $admin ??= static::currentAdmin();

        if (! $admin) {
            return [];
        }

        return StoreStaff::query()
            ->where('user_id', $admin->user_id)
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();
    }

    public static function canAccessStore(int $storeId, ?User $admin = null): bool
    {
        return in_array($storeId, static::assignedStoreIds($admin), true);
    }
}
