<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMovement extends Model
{
    public const UPDATED_AT = null;

    protected $primaryKey = 'material_movement_id';

    public const TIPE_MASUK = 'masuk';

    public const TIPE_KELUAR = 'keluar';

    protected $fillable = [
        'production_material_id',
        'tipe',
        'jumlah',
        'saldo_akhir',
        'production_order_id',
        'alasan',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'float',
            'saldo_akhir' => 'float',
        ];
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(ProductionMaterial::class, 'production_material_id', 'production_material_id');
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id', 'production_order_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }
}