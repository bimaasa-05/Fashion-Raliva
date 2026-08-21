<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'kode_permission',
        'nama_permission',
        'deskripsi',
        'status',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id')
            ->withTimestamps();
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'permission_id', 'permission_id');
    }

    public function storeStaffPermissions(): HasMany
    {
        return $this->hasMany(StoreStaffPermission::class, 'permission_id', 'permission_id');
    }
}
