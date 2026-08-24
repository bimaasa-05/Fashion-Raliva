<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $primaryKey = 'shipment_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_DIKIRIM = 'dikirim';

    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_GAGAL = 'gagal';

    protected $fillable = [
        'order_id',
        'courier_id',
        'shipping_service_id',
        'nomor_resi',
        'ongkir',
        'estimasi_tiba',
        'dikirim_pada',
        'diterima_pada',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_tiba' => 'date',
            'dikirim_pada' => 'datetime',
            'diterima_pada' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
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
