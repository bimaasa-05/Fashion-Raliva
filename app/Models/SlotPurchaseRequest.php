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

    public const PEMBAYARAN_MENUNGGU_BAYAR = 'menunggu_bayar';

    public const PEMBAYARAN_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const PEMBAYARAN_TERVERIFIKASI = 'terverifikasi';

    public const PEMBAYARAN_DITOLAK = 'ditolak';

    protected $fillable = [
        'store_id',
        'jumlah_slot',
        'harga_per_slot',
        'total_harga',
        'payment_status',
        'metode_pembayaran',
        'biaya_layanan',
        'alasan',
        'file_bukti',
        'status',
        'alasan_penolakan',
        'handled_by',
        'diajukan_pada',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
            'paid_at' => 'datetime',
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
