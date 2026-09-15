<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BahanProduksi extends Model
{
    protected $table = 'bahan_produksi';

    protected $primaryKey = 'bahan_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_id',
        'nama_bahan',
        'kategori',
        'satuan',
        'stok',
        'stok_minimum',
        'supplier_id',
        'status',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
