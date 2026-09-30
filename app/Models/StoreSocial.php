<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSocial extends Model
{
    protected $table = 'store_socials';

    protected $primaryKey = 'store_social_id';

    protected $fillable = [
        'store_id',
        'sosmed_platform_id',
        'nama_custom',
        'url',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(SosmedPlatform::class, 'sosmed_platform_id', 'sosmed_platform_id');
    }

    public function label(): string
    {
        return $this->platform?->nama_platform ?? $this->nama_custom ?? '-';
    }
}
