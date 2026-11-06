<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PostLoginRedirector;
use App\Support\SessionArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->query('expired') === '1') {
            session()->flash('info', 'Sesi Anda telah berakhir. Silakan masuk kembali untuk melanjutkan.');
        }

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
                ->withErrors(['email' => __('Email atau password salah.')]);
        }

        $user = Auth::user();

        if ($user->status !== User::STATUS_AKTIF) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('Akun tidak aktif. Hubungi administrator.')]);
        }

        return PostLoginRedirector::redirect($request, $user);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
