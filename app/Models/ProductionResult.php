<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionResult extends Model
{
    protected $primaryKey = 'production_result_id';

    protected $fillable = [
        'production_order_id',
        'jumlah_diproduksi',
        'jumlah_gagal',
        'catatan',
    ];

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id', 'production_order_id');
    }
}
