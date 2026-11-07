<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\PostLoginRedirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    private const INTENT_OWNER = 'owner';

    private const INTENT_CUSTOMER = 'customer';

    private const INTENT_SESSION_KEY = 'oauth_role_intent';

    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return $this->reject(__('Login Google belum dikonfigurasi.'));
        }

        $intent = $request->query('intent');

        if (in_array($intent, [self::INTENT_OWNER, self::INTENT_CUSTOMER], true)) {
            $request->session()->put(self::INTENT_SESSION_KEY, $intent);
        } else {
            $request->session()->forget(self::INTENT_SESSION_KEY);
        }

        $intended = $request->query('redirect');

        if (PostLoginRedirector::isSafeRedirect($intended)) {
            $request->session()->put('url.intended', $intended);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return $this->reject(__('Login Google dibatalkan.'));
        }

        if (! $this->isConfigured()) {
            return $this->reject(__('Login Google belum dikonfigurasi.'));
        }

        $intent = $request->session()->pull(self::INTENT_SESSION_KEY);
        $intent = in_array($intent, [self::INTENT_OWNER, self::INTENT_CUSTOMER], true) ? $intent : null;

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            Log::warning('Google OAuth callback gagal.', [
                'message' => $exception->getMessage(),
            ]);

            return $this->reject(__('Login Google gagal. Silakan coba lagi.'));
        }

        $googleId = trim((string) $googleUser->getId());
        $email = Str::lower(trim((string) $googleUser->getEmail()));
        $name = trim((string) $googleUser->getName());
        $emailVerified = filter_var(
            data_get($googleUser->getRaw(), 'email_verified', false),
            FILTER_VALIDATE_BOOLEAN
        );

        if ($googleId === '' || $email === '') {
            return $this->reject(__('Data akun Google tidak lengkap.'));
        }

        if (! $emailVerified) {
            return $this->reject(__('Email Google belum terverifikasi.'));
        }

        $user = User::withTrashed()->where('google_id', $googleId)->first();

        if ($user) {
            return $this->loginExistingUser($request, $user, $googleId, $intent);
        }

        $user = User::withTrashed()->where('email', $email)->first();

        if ($user) {
            return $this->loginExistingUser($request, $user, $googleId, $intent);
        }

        $user = $this->createUser($email, $name, $googleId, $intent ?? self::INTENT_CUSTOMER);

        if (! $user) {
            return $this->reject(__('Akun belum dapat dibuat. Hubungi administrator.'));
        }

        return $this->login($request, $user);
    }

    private function loginExistingUser(Request $request, User $user, string $googleId, ?string $intent): RedirectResponse
    {
        if ($user->trashed()) {
            return $this->reject(__('Akun ini sudah dihapus.'));
        }

        if ($user->status !== User::STATUS_AKTIF) {
            return $this->reject(__('Akun tidak aktif. Hubungi administrator.'));
        }

        if ($user->email_verified_at === null) {
            return $this->reject(__('Verifikasi email akun ini terlebih dahulu sebelum login dengan Google.'));
        }

        if ($user->google_id !== null && $user->google_id !== $googleId) {
            return $this->reject(__('Akun ini sudah terhubung dengan akun Google lain.'));
        }

        $roleName = $user->role?->nama_role;

        if ($intent !== null && in_array($roleName, [Role::OWNER, Role::CUSTOMER], true)) {
            $actualIntent = $roleName === Role::OWNER ? self::INTENT_OWNER : self::INTENT_CUSTOMER;

            if ($intent !== $actualIntent) {
                return $this->reject($roleName === Role::OWNER
                    ? __('Akun ini terdaftar sebagai Pemilik Bisnis. Silakan pilih Pemilik Bisnis untuk masuk.')
                    : __('Akun ini terdaftar sebagai Pelanggan. Silakan pilih Pelanggan untuk masuk.'));
            }
        }

        if ($user->google_id === null) {
            $user->forceFill(['google_id' => $googleId])->save();
        }

        return $this->login($request, $user);
    }

    private function createUser(string $email, string $name, string $googleId, string $intent): ?User
    {
        $roleName = $intent === self::INTENT_OWNER ? Role::OWNER : Role::CUSTOMER;

        $roleId = Role::query()
            ->where('nama_role', $roleName)
            ->where('status', 'aktif')
            ->value('role_id');

        if (! $roleId) {
            Log::error('Role untuk login Google tidak ditemukan.', ['role' => $roleName]);

            return null;
        }

        return User::create([
            'nama_lengkap' => $name !== '' ? $name : Str::before($email, '@'),
            'email' => $email,
            'google_id' => $googleId,
            'password' => Hash::make(Str::random(64)),
            'role_id' => $roleId,
            'email_verified_at' => now(),
            'status' => User::STATUS_AKTIF,
        ]);
    }

    private function login(Request $request, User $user): RedirectResponse
    {
        Auth::login($user);

        return PostLoginRedirector::redirect($request, $user);
    }

    private function reject(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['email' => $message]);
    }

    private function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }
}
