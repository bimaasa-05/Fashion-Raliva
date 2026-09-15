<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionOrderMaterial extends Model
{
    protected $primaryKey = 'production_order_material_id';

    protected $fillable = [
        'production_order_id',
        'production_material_id',
        'jumlah_pakai',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_pakai' => 'float',
        ];
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id', 'production_order_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(ProductionMaterial::class, 'production_material_id', 'production_material_id');
    }
}