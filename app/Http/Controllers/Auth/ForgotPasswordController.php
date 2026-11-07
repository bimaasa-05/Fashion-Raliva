<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordController extends Controller
{
    private const OTP_TTL_MINUTES = 10;

    private const OTP_MAX_ATTEMPTS = 5;

    private const OTP_RESEND_COOLDOWN_SECONDS = 60;

    private const OTP_SESSION_EMAIL_KEY = 'password_otp_email';

    public function showLinkRequestForm(): View
    {
        return view('customer.auth.forgot-password', [
            'step' => 'email',
            'email' => old('email'),
        ]);
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email wajib diisi.'),
            'email.email' => __('Format email tidak valid.'),
        ]);

        $email = Str::lower(trim((string) $request->input('email')));
        $request->session()->put(self::OTP_SESSION_EMAIL_KEY, $email);
        $rateLimitKey = 'password-otp-send:'.sha1($email);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return redirect()->route('password.otp.form')
                ->withErrors(['email' => __('Terlalu banyak permintaan kode. Silakan coba lagi nanti.')]);
        }

        $existing = PasswordResetOtp::where('email', $email)->first();

        if ($existing?->last_sent_at?->gt(now()->subSeconds(self::OTP_RESEND_COOLDOWN_SECONDS))) {
            $elapsed = max(0, now()->timestamp - $existing->last_sent_at->timestamp);
            $seconds = max(1, self::OTP_RESEND_COOLDOWN_SECONDS - $elapsed);

            return redirect()->route('password.otp.form')
                ->withErrors(['email' => __('Tunggu :seconds detik sebelum meminta kode baru.', ['seconds' => $seconds])]);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $otp = (string) random_int(100000, 999999);
            $expiresAt = now()->addMinutes(self::OTP_TTL_MINUTES);

            PasswordResetOtp::updateOrCreate(
                ['email' => $email],
                [
                    'otp_hash' => Hash::make($otp),
                    'attempts' => 0,
                    'expires_at' => $expiresAt,
                    'last_sent_at' => now(),
                ]
            );

            try {
                Mail::to($user->email)->send(new ResetPasswordOtpMail($user, $otp, $expiresAt));
            } catch (Throwable $exception) {
                Log::error('Gagal mengirim OTP reset password.', [
                    'email' => $email,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        RateLimiter::hit($rateLimitKey, 3600);

        return redirect()->route('password.otp.form');
    }

    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $email = (string) $request->session()->get(self::OTP_SESSION_EMAIL_KEY, '');

        if ($email === '') {
            return redirect()->route('password.request');
        }

        return view('customer.auth.forgot-password', [
            'step' => 'otp',
            'email' => $email,
        ]);
    }

    public function resetWithOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
        ], [
            'email.required' => __('Email wajib diisi.'),
            'email.email' => __('Format email tidak valid.'),
            'otp.required' => __('Masukkan kode OTP 6 digit.'),
            'otp.digits' => __('Masukkan kode OTP 6 digit.'),
            'password.required' => __('Password baru wajib diisi.'),
            'password.min' => __('Password minimal 8 karakter.'),
            'password.regex' => __('Password harus mengandung minimal 1 huruf kapital dan 1 angka.'),
            'password.confirmed' => __('Konfirmasi password tidak cocok.'),
        ]);

        $email = Str::lower(trim((string) $request->input('email')));
        $record = PasswordResetOtp::where('email', $email)->first();

        if (! $record || $record->expires_at->isPast()) {
            $record?->delete();

            return $this->otpError($request, $email, __('Kode OTP tidak valid atau sudah kedaluwarsa.'));
        }

        if ($record->attempts >= self::OTP_MAX_ATTEMPTS) {
            return $this->otpError($request, $email, __('Terlalu banyak percobaan. Silakan minta kode baru.'));
        }

        if (! Hash::check((string) $request->input('otp'), $record->otp_hash)) {
            $record->increment('attempts');

            return $this->otpError($request, $email, __('Kode OTP salah.'));
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $record->delete();

            return $this->otpError($request, $email, __('Kode OTP tidak valid atau sudah kedaluwarsa.'));
        }

        $user->forceFill([
            'password' => Hash::make((string) $request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();

        $record->delete();
        $request->session()->forget(self::OTP_SESSION_EMAIL_KEY);

        event(new PasswordReset($user));

        return redirect()->route('login')
            ->with('success', __('Password berhasil diubah. Silakan login dengan password baru Anda.'));
    }

    private function otpError(Request $request, string $email, string $message): RedirectResponse
    {
        $request->session()->put(self::OTP_SESSION_EMAIL_KEY, $email);

        return redirect()->route('password.otp.form')
            ->withInput($request->only('email'))
            ->withErrors(['otp' => $message]);
    }
}
