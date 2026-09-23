<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCourierSetting extends Model
{
    protected $primaryKey = 'store_courier_setting_id';

    protected $fillable = [
        'store_id',
        'courier_id',
        'shipping_service_id',
        'is_aktif',
        'ongkir_override',
        'estimasi_override',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'courier_id');
    }

    public function shippingService(): BelongsTo
    {
        return $this->belongsTo(ShippingService::class, 'shipping_service_id', 'shipping_service_id');
    }
}
