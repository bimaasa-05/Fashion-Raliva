<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMaterialRequirement extends Model
{
    protected $primaryKey = 'product_material_requirement_id';

    protected $fillable = [
        'product_id',
        'material_id',
        'nama_bahan',
        'satuan',
        'jumlah_per_unit',
        'biaya_per_unit',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_per_unit' => 'float',
            'biaya_per_unit' => 'float',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(BahanProduksi::class, 'material_id', 'bahan_id');
    }
}
