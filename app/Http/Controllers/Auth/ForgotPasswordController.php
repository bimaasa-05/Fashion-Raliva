<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if (in_array($status, [Password::RESET_LINK_SENT, Password::INVALID_USER], true)) {
            return back()->with('status', 'Jika email terdaftar, kami telah mengirim tautan reset password ke email tersebut.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Permintaan reset password terlalu sering. Silakan coba lagi nanti.']);
    }
}
