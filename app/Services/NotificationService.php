<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public static function fire(
        int $penerimaId,
        string $tipe,
        string $judul,
        string $pesan,
        ?int $aktorId = null,
        ?string $url = null,
    ): Notification {
        return Notification::create([
            'user_id' => $penerimaId,
            'aktor_id' => $aktorId ?? static::resolveActorId(),
            'tipe' => $tipe,
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => $url,
        ]);
    }

    public static function sendToRole(
        string $roleName,
        string $tipe,
        string $judul,
        string $pesan,
        ?int $aktorId = null,
        ?string $url = null,
    ): int {
        $userIds = static::userIdsByRole($roleName);

        foreach ($userIds as $userId) {
            static::fire($userId, $tipe, $judul, $pesan, $aktorId, $url);
        }

        return count($userIds);
    }

    public static function unreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    public static function firstUserByRole(string $roleName): ?User
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('nama_role', $roleName))
            ->where('status', User::STATUS_AKTIF)
            ->first();
    }

    public static function userIdsByRole(string $roleName): array
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('nama_role', $roleName))
            ->where('status', User::STATUS_AKTIF)
            ->pluck('user_id')
            ->all();
    }

    public static function markAllRead(int $userId): int
    {
        return Notification::forUser($userId)
            ->unread()
            ->update(['dibaca_pada' => now()]);
    }

    public static function resolveActorId(): ?int
    {
        return Auth::check() ? Auth::id() : null;
    }
}