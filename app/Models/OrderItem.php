<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'nama_produk_snapshot',
        'harga_snapshot',
        'quantity',
        'subtotal',
        'diskon',
        'total',
        'catatan_custom',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'product_variant_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'order_item_id', 'order_item_id');
    }

    public function refundItems(): HasMany
    {
        return $this->hasMany(RefundItem::class, 'order_item_id', 'order_item_id');
    }
}
