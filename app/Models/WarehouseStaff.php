<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStaff extends Model
{
    protected $primaryKey = 'warehouse_staff_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'warehouse_id',
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

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'warehouse_staff_permissions', 'warehouse_staff_id', 'permission_id')
            ->withPivot('warehouse_staff_permission_id', 'status')
            ->withTimestamps();
    }

    public function warehouseStaffPermissions(): HasMany
    {
        return $this->hasMany(WarehouseStaffPermission::class, 'warehouse_staff_id', 'warehouse_staff_id');
    }
}
