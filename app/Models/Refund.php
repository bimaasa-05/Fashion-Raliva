<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refund extends Model
{
    protected $primaryKey = 'refund_id';

    public const TIPE_FULL = 'full';

    public const TIPE_PARTIAL = 'partial';

    public const STATUS_REQUESTED = 'requested';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_ESKALASI = 'escalated';

    protected $fillable = [
        'order_id',
        'payment_id',
        'requested_by',
        'reviewed_by',
        'tipe_refund',
        'alasan',
        'jumlah',
        'status',
        'alasan_penolakan',
        'diajukan_pada',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RefundItem::class, 'refund_id', 'refund_id');
    }

    public function getKodeAttribute(): string
    {
        $year = $this->diajukan_pada?->format('Y') ?? now()->format('Y');
        return 'RF-' . $year . '-' . str_pad($this->refund_id, 5, '0', STR_PAD_LEFT);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'refund_id', 'refund_id');
    }
}
