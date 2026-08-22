<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commission extends Model
{
    protected $primaryKey = 'commission_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'order_id',
        'store_id',
        'persentase',
        'dasar_perhitungan',
        'jumlah_komisi',
        'status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'commission_id', 'commission_id');
    }
}
