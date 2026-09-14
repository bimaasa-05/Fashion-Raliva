<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethodAccount extends Model
{
    protected $primaryKey = 'payment_method_account_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'payment_method_id',
        'nama',
        'kode',
        'deskripsi',
        'nomor_rekening',
        'nama_pemilik',
        'file_gambar',
        'urutan',
        'status',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }
}