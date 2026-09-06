<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'nama_supplier',
        'kontak',
        'email',
        'alamat',
        'kota',
        'jenis',
        'catatan',
        'status',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Models\PurchaseOrder::class, 'supplier_id', 'supplier_id');
    }
}
