<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCategorySize extends Model
{
    protected $primaryKey = 'store_category_size_id';

    protected $fillable = [
        'store_category_id',
        'ukuran_label',
        'urutan',
    ];

    public function storeCategory(): BelongsTo
    {
        return $this->belongsTo(StoreCategory::class, 'store_category_id', 'store_category_id');
    }
}
