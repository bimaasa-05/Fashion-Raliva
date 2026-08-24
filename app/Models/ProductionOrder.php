<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends Model
{
    protected $primaryKey = 'production_order_id';

    public const PRIORITAS_RENDAH = 'rendah';

    public const PRIORITAS_NORMAL = 'normal';

    public const PRIORITAS_TINGGI = 'tinggi';

    public const PRIORITAS_URGENT = 'urgent';

    public const STATUS_REQUESTED = 'requested';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_MENUNGGU_QC = 'menunggu_qc';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'store_id',
        'requested_by',
        'assigned_to',
        'target_warehouse_id',
        'nomor_produksi',
        'prioritas',
        'status',
        'catatan',
        'dimulai_pada',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'dimulai_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    public function targetWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'target_warehouse_id', 'warehouse_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductionOrderItem::class, 'production_order_id', 'production_order_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(ProductionResult::class, 'production_order_id', 'production_order_id');
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class, 'production_order_id', 'production_order_id');
    }
}
