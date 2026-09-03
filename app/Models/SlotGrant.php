<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlotGrant extends Model
{
    protected $primaryKey = 'slot_grant_id';

    public const TIPE_GRATIS = 'gratis';

    public const TIPE_BELI = 'beli';

    public const TIPE_MANUAL = 'manual';

    protected $fillable = [
        'store_id',
        'jumlah_slot',
        'tipe',
        'keterangan',
        'ref_id',
        'ref_type',
        'created_by',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
