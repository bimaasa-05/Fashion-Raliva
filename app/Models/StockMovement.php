<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public const UPDATED_AT = null;

    protected $primaryKey = 'stock_movement_id';

    public const TIPE_MASUK = 'masuk';

    public const TIPE_KELUAR = 'keluar';

    public const TIPE_PENYESUAIAN = 'penyesuaian';

    public const TIPE_MUTASI_KELUAR = 'mutasi_keluar';

    public const TIPE_MUTASI_MASUK = 'mutasi_masuk';

    public const SUMBER_PRODUCTION_RESULT = 'production_result';

    public const SUMBER_STOCK_TRANSFER = 'stock_transfer';

    public const SUMBER_ORDER_ITEM = 'order_item';

    public const SUMBER_SUPPLIER = 'supplier';

    public const SUMBER_MANUAL = 'manual';

    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'tipe_pergerakan',
        'jumlah',
        'sumber_tipe',
        'sumber_id',
        'alasan',
        'dibuat_oleh',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'product_variant_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'sumber_id', 'supplier_id');
    }
}
