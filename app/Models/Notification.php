<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';

    public const TIPE_ORDER = 'order';
    public const TIPE_PEMBAYARAN = 'pembayaran';
    public const TIPE_PENGIRIMAN = 'pengiriman';
    public const TIPE_KOMPLAIN = 'komplain';

    public const TIPE_ULASAN = 'ulasan';

    public const TIPE_WALLET = 'wallet';
    public const TIPE_PROMO = 'promo';
    public const TIPE_SISTEM = 'sistem';

    protected $fillable = [
        'user_id',
        'aktor_id',
        'tipe',
        'judul',
        'pesan',
        'url',
        'dibaca_pada',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function aktor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aktor_id', 'user_id');
    }

    public function getIsReadAttribute(): bool
    {
        return $this->dibaca_pada !== null;
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('dibaca_pada');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Notifikasi untuk aksi yang dilakukan sendiri: tercatat unread sehingga
     * menambah badge + riwayat, dan tetap memicu popup di kanan-atas.
     */
    public static function fireSelf(string $tipe, string $judul, string $pesan, ?string $url = null): self
    {
        return static::create([
            'user_id' => Auth::id(),
            'aktor_id' => Auth::id(),
            'tipe' => $tipe,
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => $url,
        ]);
    }
}