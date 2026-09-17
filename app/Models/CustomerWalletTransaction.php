<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerWalletTransaction extends Model
{
    protected $primaryKey = 'customer_wallet_transaction_id';

    public const JENIS_TOPUP = 'topup';

    public const JENIS_PEMBAYARAN_KELUAR = 'pembayaran_keluar';

    public const JENIS_REFUND_MASUK = 'refund_masuk';

    public const JENIS_PENYESUAIAN = 'penyesuaian';

    protected $fillable = [
        'customer_wallet_id',
        'customer_topup_id',
        'order_id',
        'jenis_transaksi',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'saldo_sebelum' => 'decimal:2',
            'saldo_sesudah' => 'decimal:2',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(CustomerWallet::class, 'customer_wallet_id', 'customer_wallet_id');
    }

    public function topup(): BelongsTo
    {
        return $this->belongsTo(CustomerTopup::class, 'customer_topup_id', 'customer_topup_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}