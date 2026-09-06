<?php

namespace App\Support;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OwnerContext
{
    public static function currentOwner(): ?User
    {
        $user = Auth::user();

        if ($user && $user->role?->nama_role === Role::OWNER) {
            return $user;
        }

        return null;
    }

    public static function fallbackOwner(): ?User
    {
        return User::whereHas('role', fn ($query) => $query->where('nama_role', Role::OWNER))
            ->whereHas('ownedStores')
            ->orderBy('user_id')
            ->first();
    }

    /**
     * @return int[]
     */
    public static function ownedStoreIds(?User $owner = null): array
    {
        $owner ??= static::currentOwner();

        if (! $owner) {
            return [];
        }

        return $owner->ownedStores()->pluck('store_id')->all();
    }

    public static function firstStoreId(?User $owner = null): ?int
    {
        return static::ownedStoreIds($owner)[0] ?? null;
    }

    public static function canAccessStore(int $storeId, ?User $owner = null): bool
    {
        return in_array($storeId, static::ownedStoreIds($owner), true);
    }

    public static function currentStore(?User $owner = null): ?Store
    {
        $storeId = static::firstStoreId($owner);

        if (! $storeId) {
            return null;
        }

        return Store::find($storeId);
    }
}
