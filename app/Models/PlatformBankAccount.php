<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformBankAccount extends Model
{
    protected $primaryKey = 'platform_bank_account_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const JENIS_QRIS = 'qris';

    public const JENIS_EWALLET = 'ewallet';

    public const JENIS_BANK_TRANSFER = 'bank_transfer';

    protected $fillable = [
        'bank_id',
        'jenis',
        'nama',
        'kode',
        'deskripsi',
        'nomor_rekening',
        'nama_pemilik',
        'file_gambar',
        'urutan',
        'status',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }
}
