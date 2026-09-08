<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintMessage extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'complaint_message_id';

    protected $casts = [
        'deleted_by' => 'array',
        'edited_at' => 'datetime',
    ];

    protected $fillable = [
        'complaint_id',
        'sender_id',
        'pesan',
        'lampiran',
        'deleted_by',
        'edited_at',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'complaint_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
