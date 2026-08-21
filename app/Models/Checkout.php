<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Checkout extends Model
{
    protected $primaryKey = 'checkout_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DIBAYAR = 'dibayar';

    public const STATUS_KADALUARSA = 'kadaluarsa';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'user_id',
        'subtotal',
        'total_diskon',
        'total_pajak',
        'biaya_layanan',
        'total_ongkir',
        'grand_total',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'checkout_id', 'checkout_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'checkout_id', 'checkout_id');
    }
}
