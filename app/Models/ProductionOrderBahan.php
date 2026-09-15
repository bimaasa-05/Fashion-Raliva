<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionOrderBahan extends Model
{
    protected $table = 'production_order_bahan';
    protected $fillable = [
        'order_id',
        'bahan_id',
        'nama_bahan',
        'jumlah',
        'satuan',
        'catatan',
        'created_by',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function bahan(): BelongsTo
    {
        return $this->belongsTo(BahanProduksi::class, 'bahan_id', 'bahan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
