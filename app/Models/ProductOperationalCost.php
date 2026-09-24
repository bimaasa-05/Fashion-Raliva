<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOperationalCost extends Model
{
    protected $primaryKey = 'product_operational_cost_id';

    protected $fillable = [
        'product_id',
        'nama_biaya',
        'nominal',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'float',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
