<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreExpense extends Model
{
    protected $primaryKey = 'store_expense_id';

    protected $fillable = [
        'store_id',
        'nama',
        'kategori',
        'nominal',
        'tanggal',
        'dibuat_oleh',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }
}
