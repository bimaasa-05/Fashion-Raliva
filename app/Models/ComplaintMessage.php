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

    /**
     * Apakah viewer sudah menghapus pesan ini "untuk diri sendiri".
     */
    public function deletedFor(int $viewerId): bool
    {
        return in_array($viewerId, $this->deleted_by ?? [], true);
    }

    /**
     * Representasi pesan untuk API chat berdasar sudut pandang viewer.
     * Pesan tersembunyi (deleted untuk semua ATAU deleted_by viewer) tidak
     * mengirimkan isi pesan.
     */
    public function toChatArray(int $viewerId): array
    {
        $deleted = !is_null($this->deleted_at) || $this->deletedFor($viewerId);

        return [
            'complaint_message_id' => $this->complaint_message_id,
            'complaint_id' => $this->complaint_id,
            'sender_id' => $this->sender_id,
            'pesan' => $deleted ? null : $this->pesan,
            'lampiran' => $deleted ? null : $this->lampiran,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $deleted,
            'deleted_at' => $this->deleted_at,
            'edited_at' => $this->edited_at,
            'sender' => $this->sender ? [
                'user_id' => $this->sender->user_id,
                'nama_lengkap' => $this->sender->nama_lengkap,
                'role' => $this->sender->role?->nama_role ?? null,
            ] : null,
        ];
    }
}
