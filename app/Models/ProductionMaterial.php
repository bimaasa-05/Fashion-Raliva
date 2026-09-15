<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionMaterial extends Model
{
    protected $primaryKey = 'production_material_id';

    public const TIPE_MASUK = 'masuk';

    public const TIPE_KELUAR = 'keluar';

    protected $fillable = [
        'store_id',
        'nama_bahan',
        'satuan',
        'stok_sekarang',
        'minimal_stok',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'stok_sekarang' => 'float',
            'minimal_stok' => 'float',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class, 'production_material_id', 'production_material_id');
    }

    public function orderMaterials(): HasMany
    {
        return $this->hasMany(ProductionOrderMaterial::class, 'production_material_id', 'production_material_id');
    }
}