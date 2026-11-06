<?php

namespace App\Support;

use App\Http\Middleware\EnsureRole;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostLoginRedirector
{
    /**
     * Samakan redirect setelah login web biasa dan login Google.
     */
    public static function redirect(Request $request, User $user): RedirectResponse
    {
        $request->session()->regenerate();

        if (SessionArea::isEnabled()) {
            $area = SessionArea::areaForRole($user->role?->nama_role);

            if ($area !== null) {
                $cookie = SessionArea::cookieNameForArea($area);

                config(['session.cookie' => $cookie]);
                $request->session()->setName($cookie);
            }
        }

        $intended = $request->input('redirect');

        if (self::isSafeRedirect($intended)) {
            return redirect($intended);
        }

        if ($user->role?->nama_role === Role::OWNER) {
            $hasOperationalStore = $user->ownedStores()
                ->whereIn('status', [Store::STATUS_AKTIF, Store::STATUS_NONAKTIF])
                ->exists();

            if (! $hasOperationalStore) {
                return redirect()->route('owner.pengajuan-toko');
            }
        }

        return redirect()->intended(route(EnsureRole::homeRouteFor($user->role?->nama_role)));
    }

    public static function isSafeRedirect(mixed $intended): bool
    {
        if (! is_string($intended) || $intended === '' || str_contains($intended, '\\')) {
            return false;
        }

        // Path relatif aman dari open redirect.
        if (Str::startsWith($intended, '/') && ! Str::contains($intended, '//')) {
            return true;
        }

        // URL absolut hanya boleh same-origin.
        $base = rtrim(url('/'), '/');

        return Str::startsWith($intended, $base)
            && (strlen($intended) === strlen($base) || $intended[strlen($base)] === '/');
    }
}
