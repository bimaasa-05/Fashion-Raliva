<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSlotPackage extends Model
{
    protected $primaryKey = 'slot_package_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nama_paket',
        'harga',
        'jumlah_slot',
        'durasi_hari',
        'status',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(StoreSlotSubscription::class, 'slot_package_id', 'slot_package_id');
    }
}
