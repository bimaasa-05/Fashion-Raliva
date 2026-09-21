<?php

namespace App\Support;

use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SidebarContext
{
    public static function currentStore(?User $user = null): ?Store
    {
        $user ??= Auth::user();

        if (! $user) {
            return null;
        }

        $role = $user->role?->nama_role;

        return match ($role) {
            Role::OWNER => static::ownerStore($user),
            Role::ADMIN, Role::PRODUKSI => static::staffStore($user),
            Role::GUDANG => static::gudangStore($user),
            default => null,
        };
    }

    private static function ownerStore(User $user): ?Store
    {
        $storeId = OwnerContext::ownedStoreIds($user)[0] ?? null;

        return $storeId ? Store::find($storeId) : null;
    }

    private static function staffStore(User $user): ?Store
    {
        $storeId = StoreStaff::query()
            ->where('user_id', $user->user_id)
            ->where('status', 'aktif')
            ->orderBy('store_id')
            ->value('store_id');

        return $storeId ? Store::find($storeId) : null;
    }

    private static function gudangStore(User $user): ?Store
    {
        $warehouse = static::activeWarehouse($user);

        return $warehouse?->store;
    }

    private static function activeWarehouse(User $user): ?Warehouse
    {
        $assigned = $user->assignedWarehouses()
            ->wherePivot('status', 'aktif')
            ->where('warehouses.status', Warehouse::STATUS_AKTIF)
            ->orderBy('nama_gudang')
            ->get();

        if ($assigned->isEmpty()) {
            return null;
        }

        $activeId = Session::get('gudang_active_warehouse_id');

        if ($activeId && $assigned->contains('warehouse_id', $activeId)) {
            return $assigned->firstWhere('warehouse_id', $activeId);
        }

        Session::put('gudang_active_warehouse_id', $assigned->first()->warehouse_id);

        return $assigned->first();
    }
}