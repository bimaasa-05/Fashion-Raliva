<?php

namespace App\Http\Middleware;

use App\Support\StoreGate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreActive
{
    /**
     * Halaman yang tetap boleh diakses saat toko ditangguhkan:
     * dashboard dan seluruh halaman profil (lihat/update/foto/password).
     */
    private const ALLOWED_ROUTES = ['dashboard', 'profil'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! StoreGate::isLocked()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if ($this->isAllowed($routeName)) {
            return $next($request);
        }

        return redirect()->route(EnsureRole::homeRouteFor(Auth::user()->role?->nama_role))
            ->with('toast', [
                'message' => 'Toko Anda sedang ditangguhkan. Hanya dashboard dan profil yang dapat diakses.',
                'icon' => 'lock',
            ]);
    }

    private function isAllowed(?string $routeName): bool
    {
        if (! $routeName) {
            return false;
        }

        $segments = explode('.', $routeName);

        foreach (self::ALLOWED_ROUTES as $allowed) {
            if (in_array($allowed, $segments, true)) {
                return true;
            }
        }

        return false;
    }
}