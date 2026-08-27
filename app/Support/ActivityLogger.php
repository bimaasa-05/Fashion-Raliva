<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $aksi,
        ?string $targetTipe = null,
        ?int $targetId = null,
        ?array $nilaiLama = null,
        ?array $nilaiBaru = null,
        ?string $deskripsi = null,
        ?int $userId = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId ?? static::resolveActorId(),
            'aksi' => $aksi,
            'target_tipe' => $targetTipe,
            'target_id' => $targetId,
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => $nilaiBaru,
            'deskripsi' => $deskripsi,
        ]);
    }

    public static function resolveActorId(): int
    {
        if (Auth::check()) {
            return Auth::id();
        }

        return (int) (User::whereHas('role', fn ($query) => $query->where('nama_role', Role::SUPER_ADMIN))
            ->value('user_id') ?? 0);
    }
}
