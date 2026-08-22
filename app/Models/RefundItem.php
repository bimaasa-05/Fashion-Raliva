<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundItem extends Model
{
    protected $primaryKey = 'refund_item_id';

    protected $fillable = [
        'refund_id',
        'order_item_id',
        'quantity',
        'nominal',
        'alasan',
    ];

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'refund_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }
}
