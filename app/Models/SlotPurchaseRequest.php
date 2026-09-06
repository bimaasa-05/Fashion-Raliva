<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlotPurchaseRequest extends Model
{
    protected $primaryKey = 'slot_purchase_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'store_id',
        'jumlah_slot',
        'alasan',
        'file_bukti',
        'status',
        'alasan_penolakan',
        'handled_by',
        'diajukan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }
}
