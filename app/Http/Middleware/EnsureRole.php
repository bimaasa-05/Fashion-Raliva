<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role?->nama_role;

        if ($userRole !== $role) {
            // Different role than the authenticated user: show an "Akses Ditolak"
            // page with a button back to their own dashboard and a back button,
            // instead of silently bouncing them elsewhere.
            return response()->view('errors.access-denied', [
                'homeRoute' => $this->homeRouteFor($userRole),
            ], 403);
        }

        return $next($request);
    }

    public static function homeRouteFor(?string $role): string
    {
        return match ($role) {
            \App\Models\Role::SUPER_ADMIN => 'superadmin.dashboard',
            \App\Models\Role::OWNER => 'owner.dashboard',
            \App\Models\Role::ADMIN => 'admin.dashboard',
            \App\Models\Role::GUDANG => 'gudang.dashboard',
            \App\Models\Role::PRODUKSI => 'produksi.dashboard',
            \App\Models\Role::CUSTOMER => 'customer.home',
            default => 'login',
        };
    }
}
