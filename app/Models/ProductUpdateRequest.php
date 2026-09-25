<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUpdateRequest extends Model
{
    protected $primaryKey = 'product_update_request_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'product_id',
        'store_id',
        'requested_by',
        'status',
        'before_snapshot',
        'after_payload',
        'remove_image_ids',
        'staged_images',
        'review_note',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'before_snapshot' => 'array',
            'after_payload' => 'array',
            'remove_image_ids' => 'array',
            'staged_images' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}
