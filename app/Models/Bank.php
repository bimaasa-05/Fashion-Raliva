<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $primaryKey = 'bank_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nama_bank',
        'kode_bank',
        'status',
    ];

    public function storeBankAccounts(): HasMany
    {
        return $this->hasMany(StoreBankAccount::class, 'bank_id', 'bank_id');
    }

    public function platformBankAccounts(): HasMany
    {
        return $this->hasMany(PlatformBankAccount::class, 'bank_id', 'bank_id');
    }
}
