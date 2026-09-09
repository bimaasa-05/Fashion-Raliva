<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreCategory extends Model
{
    protected $primaryKey = 'store_category_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status',
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'kategori', 'nama_kategori');
    }
}