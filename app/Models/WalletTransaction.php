<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $primaryKey = 'wallet_transaction_id';

    public const JENIS_KOMISI_MASUK = 'komisi_masuk';

    public const JENIS_PENJUALAN_MASUK = 'penjualan_masuk';

    public const JENIS_REFUND_KELUAR = 'refund_keluar';

    public const JENIS_WITHDRAWAL = 'withdrawal';

    public const JENIS_PENYESUAIAN = 'penyesuaian';

    protected $fillable = [
        'wallet_id',
        'order_id',
        'commission_id',
        'refund_id',
        'withdrawal_id',
        'jenis_transaksi',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class, 'commission_id', 'commission_id');
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'refund_id');
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class, 'withdrawal_id', 'withdrawal_id');
    }
}
