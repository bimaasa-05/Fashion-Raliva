<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSlotSubscription extends Model
{
    protected $primaryKey = 'slot_subscription_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_KADALUARSA = 'kadaluarsa';

    protected $fillable = [
        'store_id',
        'slot_package_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'jumlah_slot',
        'slot_terpakai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_berakhir' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ProductSlotPackage::class, 'slot_package_id', 'slot_package_id');
    }
}
