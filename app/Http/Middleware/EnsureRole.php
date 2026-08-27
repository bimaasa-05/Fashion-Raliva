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
            return response()->view('errors.access-denied', [
                'message' => 'Akses Anda ditolak. Anda tidak memiliki izin untuk membuka halaman ini.',
                'homeRoute' => $this->homeRouteFor($userRole),
            ], 404);
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
