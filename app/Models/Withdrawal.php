<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Withdrawal extends Model
{
    protected $primaryKey = 'withdrawal_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBAYAR = 'dibayar';

    protected $fillable = [
        'store_id',
        'wallet_id',
        'bank_account_id',
        'reviewed_by',
        'jumlah',
        'status',
        'diajukan_pada',
        'ditinjau_pada',
        'alasan_penolakan',
        'dibayar_pada',
        'file_bukti',
        'deskripsi_bukti',
        'bukti_diupload_pada',
        'tipe_tujuan',
        'bank_id',
        'penyedia',
        'nomor_tujuan',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
            'ditinjau_pada' => 'datetime',
            'dibayar_pada' => 'datetime',
            'bukti_diupload_pada' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(StoreBankAccount::class, 'bank_account_id', 'bank_account_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    public function getTujuanJenisLabelAttribute(): string
    {
        if ($this->tipe_tujuan === 'e-wallet') {
            return 'E-wallet';
        }

        return 'Bank';
    }

    public function getTujuanPenyediaAttribute(): string
    {
        if ($this->tipe_tujuan === 'e-wallet') {
            return $this->penyedia ?: 'E-wallet';
        }

        if ($this->tipe_tujuan === 'bank' && $this->bank) {
            return $this->bank->nama_bank;
        }

        return $this->bankAccount?->bank?->nama_bank ?: 'Bank';
    }

    public function getTujuanNomorAttribute(): string
    {
        return $this->nomor_tujuan ?: ($this->bankAccount?->nomor_rekening ?? '');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'withdrawal_id', 'withdrawal_id');
    }
}
