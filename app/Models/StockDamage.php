<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockDamage extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'stock_damage';

    protected $primaryKey = 'stock_damage_id';

    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'jumlah_rusak',
        'alasan',
        'dibuat_oleh',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'product_variant_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }
}
