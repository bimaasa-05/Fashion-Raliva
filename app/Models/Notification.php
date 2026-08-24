<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';

    public const TIPE_ORDER = 'order';

    public const TIPE_PEMBAYARAN = 'pembayaran';

    public const TIPE_PENGIRIMAN = 'pengiriman';

    public const TIPE_KOMPLAIN = 'komplain';

    public const TIPE_WALLET = 'wallet';

    public const TIPE_PROMO = 'promo';

    public const TIPE_SISTEM = 'sistem';

    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'pesan',
        'dibaca_pada',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
