<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'store_id',
        'saldo_tertahan',
        'saldo_tersedia',
    ];

    protected function casts(): array
    {
        return [
            'saldo_tertahan' => 'decimal:2',
            'saldo_tersedia' => 'decimal:2',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id', 'wallet_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'wallet_id', 'wallet_id');
    }
}
