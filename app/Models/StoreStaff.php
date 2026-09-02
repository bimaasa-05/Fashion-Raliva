<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreStaff extends Model
{
    protected $primaryKey = 'store_staff_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_id',
        'user_id',
        'tanggal_penugasan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penugasan' => 'date',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'store_staff_permissions', 'store_staff_id', 'permission_id')
            ->withPivot('store_staff_permission_id', 'status')
            ->withTimestamps();
    }

    public function storeStaffPermissions(): HasMany
    {
        return $this->hasMany(StoreStaffPermission::class, 'store_staff_id', 'store_staff_id');
    }

    public function warehouseStaffPermissions(): HasMany
    {
        return $this->hasMany(WarehouseStaffPermission::class, 'warehouse_staff_id', 'warehouse_staff_id');
    }
}
