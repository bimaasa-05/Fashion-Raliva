<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_TERVERIFIKASI = 'terverifikasi';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_KADALUARSA = 'kadaluarsa';

    protected $fillable = [
        'checkout_id',
        'topup_id',
        'payment_method_id',
        'payment_account_id',
        'jumlah',
        'jumlah_saldo',
        'status',
        'batas_waktu',
        'dibayar_pada',
    ];

    /**
     * Sisa nominal yang dibayar lewat metode eksternal
     * (QRIS / E-Wallet / Bank Transfer) pada pembayaran campuran.
     */
    public function getSisaTransferAttribute(): float
    {
        return max(0, (float) $this->jumlah - (float) $this->jumlah_saldo);
    }

    public function topup(): BelongsTo
    {
        return $this->belongsTo(CustomerTopup::class, 'topup_id', 'customer_topup_id');
    }

    protected function casts(): array
    {
        return [
            'batas_waktu' => 'datetime',
            'dibayar_pada' => 'datetime',
        ];
    }

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'checkout_id', 'checkout_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(PlatformBankAccount::class, 'payment_account_id', 'platform_bank_account_id');
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class, 'payment_id', 'payment_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(PaymentVerification::class, 'payment_id', 'payment_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'payment_id', 'payment_id');
    }
}
