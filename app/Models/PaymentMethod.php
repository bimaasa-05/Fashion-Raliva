<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $primaryKey = 'payment_method_id';

    public const KODE_QRIS = 'qris';

    public const KODE_EWALLET = 'ewallet';

    public const KODE_BANK_TRANSFER = 'bank_transfer';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'kode_metode',
        'nama_metode',
        'batas_waktu_menit',
        'status',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_method_id', 'payment_method_id');
    }
}
