<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpname extends Model
{
    public const UPDATED_AT = null;

    protected $primaryKey = 'stock_opname_id';

    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'catatan',
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
