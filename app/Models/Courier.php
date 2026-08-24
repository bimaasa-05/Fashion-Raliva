<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    protected $primaryKey = 'courier_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nama_kurir',
        'kode_kurir',
        'status',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(ShippingService::class, 'courier_id', 'courier_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'courier_id', 'courier_id');
    }
}
