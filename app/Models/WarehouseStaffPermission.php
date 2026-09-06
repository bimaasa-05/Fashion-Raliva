<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStaffPermission extends Model
{
    protected $primaryKey = 'warehouse_staff_permission_id';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'warehouse_staff_id',
        'permission_id',
        'status',
    ];

    public function warehouseStaff(): BelongsTo
    {
        return $this->belongsTo(WarehouseStaff::class, 'warehouse_staff_id', 'warehouse_staff_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'permission_id');
    }
}