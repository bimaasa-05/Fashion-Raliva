<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SosmedPlatform extends Model
{
    protected $table = 'sosmed_platforms';

    protected $primaryKey = 'sosmed_platform_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nama_platform',
        'status',
    ];

    public function storeSocials(): HasMany
    {
        return $this->hasMany(StoreSocial::class, 'sosmed_platform_id', 'sosmed_platform_id');
    }
}
