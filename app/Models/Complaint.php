<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $primaryKey = 'complaint_id';

    public const KATEGORI_PRODUK = 'produk';

    public const KATEGORI_PENGIRIMAN = 'pengiriman';

    public const KATEGORI_PELAYANAN = 'pelayanan';

    public const KATEGORI_LAINNYA = 'lainnya';

    public const STATUS_OPEN = 'open';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DITUTUP = 'ditutup';

    public const STATUS_ESKALASI = 'escalated';

    protected $fillable = [
        'user_id',
        'order_id',
        'order_item_id',
        'store_id',
        'kategori',
        'subjek',
        'deskripsi',
        'status',
        'dibuat_pada',
        'diselesaikan_pada',
    ];

    protected function casts(): array
    {
        return [
            'dibuat_pada' => 'datetime',
            'diselesaikan_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ComplaintMessage::class, 'complaint_id', 'complaint_id');
    }
}
