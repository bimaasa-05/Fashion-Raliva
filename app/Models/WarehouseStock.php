<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStock extends Model
{
    public const CREATED_AT = null;

    protected $primaryKey = 'warehouse_stock_id';

    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'jumlah_stok',
        'jumlah_direservasi',
        'stok_minimum',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'product_variant_id');
    }
}
