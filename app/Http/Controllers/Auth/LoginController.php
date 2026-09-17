<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureRole;
use App\Models\User;
use App\Support\SessionArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        return view('customer.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Remember-me nonaktif saat multi-sesi aktif (cookie remember_web_* sama antar-area).
        $remember = $request->boolean('remember') && ! SessionArea::isEnabled();

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        if ($user->status !== User::STATUS_AKTIF) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun tidak aktif. Hubungi administrator.']);
        }

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
        if (is_string($intended) && Str::startsWith($intended, '/') && ! Str::contains($intended, '//')) {
            return redirect($intended);
        }

        return redirect()->intended(route(EnsureRole::homeRouteFor($user->role?->nama_role)));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
