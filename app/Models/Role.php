<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $primaryKey = 'role_id';

    public const SUPER_ADMIN = 'Super Admin';

    public const OWNER = 'Owner';

    public const ADMIN = 'Admin';

    public const PRODUKSI = 'Produksi';

    public const GUDANG = 'Gudang';

    public const CUSTOMER = 'Customer';

    protected $fillable = [
        'nama_role',
        'deskripsi',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'role_id');
    }
}
