<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use App\Support\PostLoginRedirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
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

        // Cookie sesi terikat ke host. Jika user membuka 127.0.0.1 sementara
        // callback Google di localhost, state OAuth akan hilang. Pindahkan
        // ke origin callback sebelum menyimpan intent/state.
        if ($redirect = $this->redirectToCallbackOrigin($request)) {
            return $redirect;
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
        $hadState = $request->session()->has('state');

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $exception) {
            Log::warning('Google OAuth state tidak cocok.', [
                'exception' => $exception::class,
                'host' => $request->getHost(),
                'had_state' => $hadState,
            ]);

            return $this->reject(__('Sesi login Google kedaluwarsa. Silakan coba lagi.'));
        } catch (Throwable $exception) {
            Log::warning('Google OAuth callback gagal.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'host' => $request->getHost(),
                'had_state' => $hadState,
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

        $user = User::create([
            'nama_lengkap' => $name !== '' ? $name : Str::before($email, '@'),
            'email' => $email,
            'google_id' => $googleId,
            'password' => Hash::make(Str::random(64)),
            'role_id' => $roleId,
            'email_verified_at' => now(),
            'status' => User::STATUS_AKTIF,
        ]);

        NotificationService::sendWelcome($user);

        return $user;
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

    private function redirectToCallbackOrigin(Request $request): ?RedirectResponse
    {
        $callback = parse_url((string) config('services.google.redirect'));

        if (! is_array($callback) || empty($callback['scheme']) || empty($callback['host'])) {
            return null;
        }

        if (Str::lower($request->getHost()) === Str::lower($callback['host'])) {
            return null;
        }

        $origin = $callback['scheme'].'://'.$callback['host'];

        if (isset($callback['port'])) {
            $origin .= ':'.$callback['port'];
        }

        $query = $request->query();

        if (isset($query['redirect'])) {
            $query['redirect'] = $this->rewriteRedirectOrigin(
                $query['redirect'],
                $origin,
                $request->getSchemeAndHttpHost()
            );
        }

        $path = $request->getBaseUrl().$request->getPathInfo();

        return redirect()->away($origin.$path.($query !== [] ? '?'.http_build_query($query) : ''));
    }

    private function rewriteRedirectOrigin(mixed $redirect, string $canonicalOrigin, string $currentOrigin): mixed
    {
        if (! is_string($redirect) || $redirect === '') {
            return $redirect;
        }

        $parts = parse_url($redirect);

        if (! is_array($parts) || empty($parts['host'])) {
            return $redirect;
        }

        $canonicalHost = parse_url($canonicalOrigin, PHP_URL_HOST);
        $currentHost = parse_url($currentOrigin, PHP_URL_HOST);

        if (! is_string($canonicalHost) || ! is_string($currentHost)) {
            return $redirect;
        }

        if (! in_array(Str::lower($parts['host']), [Str::lower($canonicalHost), Str::lower($currentHost)], true)) {
            return $redirect;
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? '?'.$parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#'.$parts['fragment'] : '';

        return rtrim($canonicalOrigin, '/').$path.$query.$fragment;
    }

    private function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }
}
