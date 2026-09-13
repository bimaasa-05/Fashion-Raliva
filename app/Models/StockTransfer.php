<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTransfer extends Model
{
    protected $primaryKey = 'stock_transfer_id';

    public const STATUS_REQUESTED = 'requested';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_IN_TRANSIT = 'in_transit';

    public const STATUS_RECEIVED = 'received';

    public const STATUS_CANCELLED = 'cancelled';

    public const ALLOWED_TRANSITIONS = [
        self::STATUS_REQUESTED => [self::STATUS_APPROVED, self::STATUS_CANCELLED],
        self::STATUS_APPROVED => [self::STATUS_RECEIVED, self::STATUS_CANCELLED],
        self::STATUS_IN_TRANSIT => [self::STATUS_RECEIVED, self::STATUS_CANCELLED],
        self::STATUS_RECEIVED => [],
        self::STATUS_CANCELLED => [],
    ];

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'requested_by',
        'approved_by',
        'status',
        'diminta_pada',
        'diterima_pada',
        'alasan_penolakan',
        'dibatalkan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diminta_pada' => 'datetime',
            'diterima_pada' => 'datetime',
            'dibatalkan_pada' => 'datetime',
        ];
    }

    public function canTransitionTo(string $statusBaru): bool
    {
        return in_array($statusBaru, self::ALLOWED_TRANSITIONS[$this->status] ?? [], true);
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'warehouse_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockTransferItem::class, 'stock_transfer_id', 'stock_transfer_id');
    }
}
