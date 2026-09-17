<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerWallet extends Model
{
    protected $primaryKey = 'customer_wallet_id';

    protected $fillable = [
        'user_id',
        'saldo_tersedia',
    ];

    protected function casts(): array
    {
        return [
            'saldo_tersedia' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerWalletTransaction::class, 'customer_wallet_id', 'customer_wallet_id');
    }
}