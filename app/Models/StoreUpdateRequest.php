<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreUpdateRequest extends Model
{
    protected $primaryKey = 'store_update_request_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'store_id',
        'nama_toko',
        'kategori',
        'deskripsi',
        'alamat',
        'nomor_telepon',
        'status',
        'alasan_penolakan',
        'reviewed_by',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
