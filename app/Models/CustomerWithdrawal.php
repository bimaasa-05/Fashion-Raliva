<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerWithdrawal extends Model
{
    protected $primaryKey = 'customer_withdrawal_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBAYAR = 'dibayar';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const TIPE_BANK = 'bank';

    public const TIPE_EWALLET = 'e-wallet';

    protected $fillable = [
        'user_id',
        'jumlah',
        'fee',
        'jumlah_bersih',
        'tipe_tujuan',
        'bank_id',
        'penyedia',
        'nomor_tujuan',
        'nama_pemilik',
        'status',
        'diajukan_pada',
        'diproses_pada',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'fee' => 'decimal:2',
            'jumlah_bersih' => 'decimal:2',
            'diajukan_pada' => 'datetime',
            'diproses_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }
}
