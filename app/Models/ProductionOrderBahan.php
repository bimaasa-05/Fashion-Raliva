<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionOrderBahan extends Model
{
    protected $table = 'production_order_bahan';

    public const SUMBER_ADMIN = 'admin';

    public const SUMBER_PRODUKSI = 'produksi';

    public const SATUAN = ['meter', 'cm', 'yard', 'roll', 'kg', 'gram', 'pcs'];

    protected $fillable = [
        'order_id',
        'bahan_id',
        'nama_bahan',
        'jumlah',
        'satuan',
        'catatan',
        'sumber',
        'dibuat_oleh_role',
        'created_by',
    ];

    public function isDariProduksi(): bool
    {
        return $this->sumber === self::SUMBER_PRODUKSI
            || $this->dibuat_oleh_role === 'Produksi';
    }

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
