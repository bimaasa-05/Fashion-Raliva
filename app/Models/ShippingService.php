<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingService extends Model
{
    protected $primaryKey = 'shipping_service_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_id',
        'courier_id',
        'nama_layanan',
        'estimasi_hari',
        'tarif',
        'tarif_sekota',
        'status',
    ];

    protected $casts = [
        'tarif' => 'float',
        'tarif_sekota' => 'float',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'courier_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'shipping_service_id', 'shipping_service_id');
    }
}
