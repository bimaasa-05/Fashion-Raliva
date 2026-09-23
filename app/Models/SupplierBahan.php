<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierBahan extends Model
{
    protected $table = 'supplier_bahan';

    protected $primaryKey = 'supplier_bahan_id';

    protected $fillable = [
        'supplier_id',
        'nama_bahan',
        'satuan',
        'jumlah',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}