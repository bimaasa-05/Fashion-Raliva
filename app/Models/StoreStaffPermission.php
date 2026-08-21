<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreStaffPermission extends Model
{
    protected $primaryKey = 'store_staff_permission_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_staff_id',
        'permission_id',
        'status',
    ];

    public function storeStaff(): BelongsTo
    {
        return $this->belongsTo(StoreStaff::class, 'store_staff_id', 'store_staff_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'permission_id');
    }
}
