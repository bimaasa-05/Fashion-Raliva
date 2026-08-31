<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menegakkan akses berdasarkan kode permission (dari tabel role_permissions),
 * bukan sekadar nama role. Dipasang setelah middleware 'role' agar tetap
 * menjaga pembatasan per-role secara kasar.
 *
 * Pemakaian: ->middleware(['auth', 'role:Gudang', 'permission:warehouse.stock_in'])
 */
class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $kodePermission): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! Auth::user()->hasPermission($kodePermission)) {
            return response()->view('errors.access-denied', [
                'homeRoute' => EnsureRole::homeRouteFor(Auth::user()->role?->nama_role),
                'message'   => 'Anda tidak memiliki izin ('.$kodePermission.') untuk melakukan tindakan ini.',
            ], 403);
        }

        return $next($request);
    }
}
