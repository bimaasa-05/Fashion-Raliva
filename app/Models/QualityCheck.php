<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityCheck extends Model
{
    protected $primaryKey = 'quality_check_id';

    public const STATUS_LULUS = 'lulus';

    public const STATUS_GAGAL = 'gagal';

    public const STATUS_SEBAGIAN = 'sebagian';

    protected $fillable = [
        'production_order_id',
        'checked_by',
        'jumlah_lulus',
        'jumlah_gagal',
        'status',
        'catatan',
        'diperiksa_pada',
    ];

    protected function casts(): array
    {
        return [
            'diperiksa_pada' => 'datetime',
        ];
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id', 'production_order_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by', 'user_id');
    }
}
