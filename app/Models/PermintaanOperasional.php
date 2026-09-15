<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanOperasional extends Model
{
    protected $table = 'permintaan_operasional';
    protected $primaryKey = 'permintaan_id';

    protected $fillable = [
        'store_id',
        'pemohon_id',
        'jenis_permintaan',
        'judul',
        'deskripsi',
        'payload',
        'status',
        'admin_id',
        'catatan_admin',
        'diproses_pada',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'diproses_pada' => 'datetime',
        ];
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    public const JENIS = [
        'stok' => 'Stok',
        'produksi' => 'Produksi',
        'gudang' => 'Gudang',
        'pengiriman' => 'Pengiriman',
        'supplier' => 'Supplier',
        'lainnya' => 'Lainnya',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemohon_id', 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}
