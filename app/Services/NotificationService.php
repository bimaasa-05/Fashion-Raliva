<?php

namespace App\Services;

use App\Http\Middleware\EnsureRole;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

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

    /**
     * Notifikasi selamat datang untuk akun yang baru dibuat.
     *
     * Kegagalan membuat notifikasi tidak boleh menggagalkan pembuatan akun,
     * jadi seluruh proses dibungkus dan hanya dicatat sebagai warning.
     */
    public static function sendWelcome(User $user, ?User $actor = null, ?Store $store = null): ?Notification
    {
        // Customer tidak lagi memiliki fitur notifikasi. Welcome hanya untuk
        // Owner dan karyawan internal.
        if ($user->role?->nama_role === Role::CUSTOMER) {
            return null;
        }

        try {
            $roleName = $user->role?->nama_role;

            $judul = __('Welcome to :brand!', ['brand' => brand()]);

            $pesan = match ($roleName) {
                Role::OWNER => __('Your business owner account is ready. Complete your store application from the dashboard.'),
                Role::ADMIN, Role::PRODUKSI, Role::GUDANG => $store
                    ? __('You have been added as :role at :store. Open your dashboard to get started.', ['role' => $roleName, 'store' => $store->nama_toko])
                    : __('Your account has been created with the :role role. Open your dashboard to get started.', ['role' => $roleName]),
                default => __('Your account is ready. Open your dashboard to get started.'),
            };

            return static::fire(
                $user->user_id,
                Notification::TIPE_SISTEM,
                $judul,
                $pesan,
                $actor?->user_id,
                route(EnsureRole::homeRouteFor($roleName)),
            );
        } catch (Throwable $exception) {
            Log::warning('Gagal membuat welcome notification.', [
                'user_id' => $user->user_id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
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

    public static function sendToRoleInStores(
        string $roleName,
        array $storeIds,
        string $tipe,
        string $judul,
        string $pesan,
        ?int $aktorId = null,
        ?string $url = null,
    ): int {
        $userIds = User::query()
            ->whereHas('role', fn ($q) => $q->where('nama_role', $roleName))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->whereIn('store_id', $storeIds)->where('status', 'aktif'))
            ->pluck('user_id')
            ->all();

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
