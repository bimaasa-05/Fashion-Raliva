<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $primaryKey = 'activity_log_id';

    protected $fillable = [
        'user_id',
        'aksi',
        'target_tipe',
        'target_id',
        'nilai_lama',
        'nilai_baru',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_lama' => 'array',
            'nilai_baru' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
