<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdSlot extends Model
{
    protected $primaryKey = 'ad_slot_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_DITUNDA = 'ditunda';

    public const PAYMENT_MENUNGGU = 'menunggu_verifikasi';

    public const PAYMENT_TERVERIFIKASI = 'terverifikasi';

    public const PAYMENT_DITOLAK = 'ditolak';

    protected $fillable = [
        'product_id',
        'store_id',
        'nominal_bid',
        'payment_status',
        'metode_pembayaran',
        'file_bukti',
        'platform_bank_account_id',
        'paid_at',
        'handled_by',
        'alasan_penolakan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nominal_bid' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(PlatformBankAccount::class, 'platform_bank_account_id', 'platform_bank_account_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }
}
