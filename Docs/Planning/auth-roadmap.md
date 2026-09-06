# Auth Roadmap — Raliva Fashion

Planning document for authentication features deferred to a later phase.

## Current State (Phase A — done)
- Unified `/login` + `/register` for all roles (real auth, no mock).
- `EnsureRole` middleware gates each role group; cross-role access now returns
  HTTP **404 "Akses Ditolak"** (`resources/views/errors/access-denied.blade.php`).
  Unauthenticated users are redirected to `/login`.
- Customer nav links (drawer, bottom-nav, wishlist buttons, account menu) are now
  conditional on `auth()->check()` so logged-in customers no longer bounce to home.
- `LoginController` honors a `redirect` query param after successful login.
- `UserSeeder` seeds one user per role with proper store/warehouse/staff context.
- Session: standard single Laravel session per browser. To test multiple roles at
  once, use incognito / a separate browser profile (logging in a new role in another
  tab overwrites the shared session).
- **Rate limiting (added):** `POST /login` and `POST /register` are now throttled
  with `throttle:5,1` (max 5 attempts per 1 minute per IP). Protects against
  brute-force / credential stuffing on the unified auth forms.

## Phase B — Deferred (pending)
### 1. Forgot Password — Gmail OTP flow
Goal: user enters email → system sends a 6-digit OTP via Gmail SMTP → user verifies
OTP → if correct, show password-reset form; if wrong, redirect back with error.

Proposed implementation:
- Controller `Auth\PasswordResetController`:
  - `showEmailForm()` → view email input.
  - `sendOtp(Request)` → validate email exists; generate 6-digit OTP; store in
    `Cache::put("pwd_otp_{$email}", $otp, now()->addMinutes(10))`; send
    `SendOtpMail` Mailable via Gmail SMTP.
  - `showVerifyForm()` → OTP input view (carries email).
  - `verifyOtp(Request)` → compare to cache; on match set session flag
    `otp_verified_{$email}` and redirect to reset form; on mismatch redirect back
    with error "Kode OTP salah.".
  - `showResetForm()` → new-password form (only if session flag set).
  - `resetPassword(Request)` → validate + update `User::password`; clear cache/session;
    redirect to login with success message (optionally auto-login).
- Mailable `App\Mail\SendOtpMail` + view `resources/views/emails/otp.blade.php`.
- Routes (replace existing static `password.request` / `password.reset`):
  - `GET|POST /forgot-password`  (email form + send OTP)
  - `GET|POST /verify-otp`      (verify OTP)
  - `GET|POST /reset-password`  (reset form + update)
- Config: real Gmail SMTP in `.env`
  (`MAIL_MAILER=smtp`, `MAIL_HOST=smtp.gmail.com`, `MAIL_PORT=587`,
  `MAIL_USERNAME`, `MAIL_PASSWORD` = Google app password, `MAIL_ENCRYPTION=tls`).
  Dev fallback: keep `MAIL_MAILER=log` so OTP is written to
  `storage/logs/laravel.log` and the flow stays testable.

### 2. Google Login (OAuth)
Goal: allow registration/login with a Google account.
- Install `laravel/socialite`.
- `config/services.php` add `google` (client id/secret from Google Cloud).
- Routes: `GET /auth/google` (redirect) and `GET /auth/google/callback`.
- Controller `Auth\GoogleController`:
  - On callback, find user by `email`; if exists, log in (must match an allowed
    role or be a Customer). If not, optionally auto-create a Customer account, then log in.
  - Respect `EnsureRole` redirect after login.
- UI: "Continue with Google" button on login + register pages.

## Open Questions
- Forgot-password: does the user want auto-login after reset, or just redirect to login?
- Google login: which roles may sign in with Google (Customer only, or also Owner self-register)?
- Gmail credentials: provide a Google app password, or use `log` driver for dev?
