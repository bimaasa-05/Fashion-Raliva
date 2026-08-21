<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $primaryKey = 'warehouse_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_id',
        'nama_gudang',
        'alamat',
        'nomor_telepon',
        'status',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function warehouseStaff(): HasMany
    {
        return $this->hasMany(WarehouseStaff::class, 'warehouse_id', 'warehouse_id');
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'warehouse_staff', 'warehouse_id', 'user_id')
            ->withPivot('warehouse_staff_id', 'tanggal_penugasan', 'status')
            ->withTimestamps();
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id', 'warehouse_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'warehouse_id', 'warehouse_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'target_warehouse_id', 'warehouse_id');
    }

    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id', 'warehouse_id');
    }

    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'to_warehouse_id', 'warehouse_id');
    }
}
