<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $primaryKey = 'promotion_id';

    public const TIPE_PORSENTE = 'persen';

    public const TIPE_NOMINAL = 'nominal';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'creator_id',
        'store_id',
        'kode_promo',
        'nama_promo',
        'tipe_diskon',
        'nilai_diskon',
        'minimal_pembelian',
        'maksimal_diskon',
        'mulai_pada',
        'berakhir_pada',
        'dapat_digabung',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'mulai_pada' => 'datetime',
            'berakhir_pada' => 'datetime',
            'dapat_digabung' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'user_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(PromotionProduct::class, 'promotion_id', 'promotion_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(PromotionCategory::class, 'promotion_id', 'promotion_id');
    }
}
