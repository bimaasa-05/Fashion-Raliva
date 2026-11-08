# Revisi Login, Bahasa, Landing Penawaran, dan Google Login

Tanggal dibuat: 2026-11-06  
Status: Fase 0-5 automated regression pass (339 passed, 4 skipped, 0 failed); browser manual QA dan deployment pending
Repo: `C:\laragon\www\Raliva-Fashion`  
Branch: `main`  
Baseline commit: `a1726835`  
Working tree saat plan dibuat: clean

Dokumen ini adalah **satu file plan** untuk seluruh pekerjaan. Semua fase, file, test, dan acceptance criteria ada di sini. Jangan dipecah menjadi file plan terpisah.

## Status eksekusi

- [x] Fase 0 — baseline
- [x] Fase 1 — shadow login light mode
- [x] Fase 2 — Bahasa Indonesia default
- [x] Fase 3 — penawaran landing editable di Superadmin
- [x] Fase 4 — Google Login (implementasi + automated test; verifikasi browser pending)
- [x] Fase 4b — Google role modal + OTP reset password (implementasi + automated test; verifikasi browser pending)
- [x] Fase 4c — perbaikan state OAuth Google (host mismatch)
- [x] Fase 4d — welcome notification MVP (in-app; automated test pass, browser QA pending)
- [x] Fase 5 — regression penuh (automated pass; 4 skip data-dependent; browser manual QA pending)

---

## 1. Tujuan

Menyelesaikan empat revisi sebelum kembali ke rencana deployment hosting:

1. Menghilangkan shadow pada border card login di white mode.
2. Menjadikan Bahasa Indonesia sebagai bahasa default website.
3. Membuat konten section penawaran di landing page `/join#daftar` bisa diedit dari dashboard Superadmin.
4. Mengaktifkan Google Login yang benar-benar berfungsi.
5. Menambahkan pilihan role Owner/Customer pada Google Login dan mengganti reset password link dengan OTP.

Prinsip kerja:

- Jangan eksekusi semua sekaligus.
- Kerjakan satu fase, lalu berhenti untuk review dan test.
- Setelah satu fase lolos, baru lanjut fase berikutnya.
- Jangan menyentuh hosting/deployment pada tahap ini.
- Jangan mengubah data production.
- Commit satu file per commit, `--no-verify`, tidak push.
- Jangan commit `.env`.

---

## 2. Keputusan yang sudah dikunci

| Topik | Keputusan |
|---|---|
| Shadow login | Hapus shadow card login di light mode; border tetap |
| Bahasa default | `id` untuk locale dan fallback |
| Landing penawaran | Tetap di `/join#daftar`, konten editable dari Superadmin |
| Google Login | Diaktifkan, bukan disembunyikan |
| Google button | Hanya di halaman login; tombol di register dihapus |
| Google role modal | Klik Google membuka pilihan **Pemilik Bisnis** atau **Pelanggan** |
| Akun Google baru | Dibuat langsung sesuai pilihan modal; tanpa intent default Customer |
| Owner via Google | Langsung dibuat Owner tanpa toko, login, lalu diarahkan ke `owner.pengajuan-toko` |
| Akun Google lama | Role tersimpan menang; pilihan modal yang berbeda ditolak dengan pesan |
| Upgrade Customer → Owner | Tidak dibuat |
| Lupa password | OTP 6 digit; link reset lama dihapus |
| Eksekusi | Satu fase per checkpoint, tidak sekaligus |
| Reset data dev | `php artisan migrate:fresh --seed` disetujui untuk dev |
| Deployment | Ditunda sampai revisi selesai dan test pass |

---

## 3. Aturan eksekusi per fase

Setiap fase mengikuti alur:

1. Baca file terkait.
2. Implementasi perubahan minimal.
3. Jalankan test targeted.
4. Cek manual di browser jika perlu.
5. Review diff.
6. Commit satu file per commit.
7. Berhenti dan minta konfirmasi sebelum fase berikutnya.

Jangan lanjut ke fase berikutnya tanpa checkpoint.

---

## 4. Temuan awal dari codebase

- Shadow login berasal dari `.frame-gold` di:
  - `resources/views/customer/auth/login.blade.php`
- `.env` saat ini memakai:
  ```env
  APP_LOCALE=en
  APP_FALLBACK_LOCALE=en
  ```
  Padahal `config/app.php` default-nya sudah `id`.
- Section penawaran `/join#daftar` masih hardcoded di:
  - `lang/id/landing.php`
  - `lang/en/landing.php`
- Yang sudah editable dari Superadmin baru FAQ Landing:
  - `app/Models/LandingFaq.php`
  - `resources/views/SuperAdmin/pengaturan-sistem/_landing-faq.blade.php`
- Tombol Google di login/register masih `<a href="#">`.
- Belum ada:
  - package `laravel/socialite`
  - route OAuth Google
  - kolom `google_id` di tabel `users`
- `bootstrap/cache/config.php` tidak ada adalah normal; bukan penyebab error.

---

## 5. Fase 0 — Baseline dan pengamanan

### Tujuan

Memastikan kondisi awal bersih dan test baseline diketahui.

### Langkah

- [ ] Cek status Git:
  ```bash
  git status --short
  git log -1 --oneline
  ```
- [ ] Jalankan test baseline:
  ```bash
  php artisan test
  ```
- [ ] Catat test yang gagal/skip sebelum perubahan.
- [ ] Pastikan tidak ada perubahan lokal yang belum di-commit.
- [ ] Backup file yang akan disentuh jika diperlukan.

### Acceptance

- Working tree clean.
- Baseline test diketahui.
- Tidak ada perubahan kode pada fase ini.

---

## 6. Fase 1 — Hilangkan shadow border login (white mode)

### Scope

Hanya halaman login. Dark mode tidak diubah. Register/forgot-password tidak diubah kecuali diminta terpisah.

### File

- `resources/views/customer/auth/login.blade.php`

### Perubahan

Di blok CSS light mode, ubah:

```css
html:not(.theme-dark) .frame-gold {
    box-shadow: none;
}
```

Yang dipertahankan:

- `border-2 border-secondary`
- Layout card
- Dark mode shadow

Jika setelah dicek masih ada glow tipis di dalam border, hapus juga inset glow pada light mode.

### Verifikasi

- [ ] Buka `/login` di white mode.
- [ ] Card login hanya punya border, tanpa shadow.
- [ ] Dark mode masih normal.
- [ ] Tidak ada layout shift.
- [ ] Cek di mobile dan desktop.

### Commit

```text
style: hapus shadow card login light mode
```

### Acceptance

Shadow card login hilang di white mode, border tetap terlihat.

---

## 7. Fase 2 — Bahasa Indonesia sebagai default

### Scope

Default locale website menjadi `id`, tanpa menghapus pilihan EN.

### File

- `.env` (lokal; tidak di-commit)
- `.env.example` (di-commit)
- Audit:
  - `lang/id.json`
  - `lang/id/landing.php`
  - `lang/id/validation.php`
- Opsional:
  - `app/Http/Middleware/SetLocale.php` (kemungkinan tidak perlu diubah)

### Perubahan

`.env` dan `.env.example`:

```env
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
```

Catatan:

- `config/app.php` sudah memakai `id`.
- `SetLocale` sudah membaca `config('app.locale')`.
- Session lama yang memilih `en` akan tetap `en` sampai user mengganti atau session dibersihkan.
- Language switcher ID/EN tetap dipertahankan.

### Audit terjemahan

- [ ] Cek halaman login.
- [ ] Cek landing `/join`.
- [ ] Cek dashboard tiap role.
- [ ] Cek pesan validasi umum.
- [ ] Cek halaman yang memakai `__()` dengan string Indonesia.
- [ ] Tambahkan mapping ke `lang/id.json` jika ada yang masih tampil English.

### Test

- [ ] Incognito: landing otomatis Indonesia.
- [ ] Incognito: login otomatis Indonesia.
- [ ] Switch ke EN berhasil.
- [ ] Switch kembali ke ID berhasil.
- [ ] Test `LocaleSwitcherTest` tetap pass.

### Commit

```text
chore: jadikan bahasa indonesia sebagai locale default
```

Jika ada file terjemahan yang diubah, commit terpisah per file.

### Acceptance

Pengunjung baru melihat website dalam Bahasa Indonesia secara default.

---

## 8. Fase 3 — Landing penawaran editable di Superadmin

### Scope

Section yang diedit adalah section **Daftar / Buka Toko** di:

```text
/join#daftar
```

Tidak membuat halaman baru. Tidak memindahkan section.

### Pendekatan data

Gunakan tabel `settings` yang sudah ada. Tidak perlu migration baru.

Gunakan key dengan prefix `landing_offer_`.

### Field yang bisa diedit

- Eyebrow
- Judul
- Deskripsi
- Benefit 1
- Benefit 2
- Benefit 3
- Judul kebutuhan
- Requirement 1
- Requirement 2
- Requirement 3
- Catatan requirement
- CTA utama
- CTA sekunder
- Catatan komisi
- Versi ID dan EN untuk semua field di atas

Contoh key:

```text
landing_offer_eyebrow_id
landing_offer_eyebrow_en
landing_offer_title_id
landing_offer_title_en
landing_offer_description_id
landing_offer_description_en
landing_offer_benefit_1_id
landing_offer_benefit_1_en
landing_offer_benefit_2_id
landing_offer_benefit_2_en
landing_offer_benefit_3_id
landing_offer_benefit_3_en
landing_offer_requirements_title_id
landing_offer_requirements_title_en
landing_offer_requirement_1_id
landing_offer_requirement_1_en
landing_offer_requirement_2_id
landing_offer_requirement_2_en
landing_offer_requirement_3_id
landing_offer_requirement_3_en
landing_offer_requirement_note_id
landing_offer_requirement_note_en
landing_offer_cta_primary_id
landing_offer_cta_primary_en
landing_offer_cta_secondary_id
landing_offer_cta_secondary_en
landing_offer_commission_note_id
landing_offer_commission_note_en
```

### Fallback

Jika setting kosong, landing tetap memakai teks lama dari:

- `landing.open_store.eyebrow`
- `landing.open_store.title`
- `landing.open_store.description`
- `landing.common.free_registration`
- `landing.common.verified_store`
- `landing.common.owner_dashboard`
- `landing.open_store.requirements_title`
- `landing.open_store.requirement_1`
- `landing.open_store.requirement_2`
- `landing.open_store.requirement_3`
- `landing.open_store.requirement_note`
- `landing.open_store.cta_primary`
- `landing.open_store.cta_secondary`
- `landing.open_store.commission_note`

Placeholder `{percent}` dan `:percent` diganti dengan nilai komisi platform.

### File

- `app/Http/Controllers/LandingController.php`
  - Tambah data `$landingOffer`.
- `resources/views/landing/index.blade.php`
  - Section `#daftar` memakai `$landingOffer`.
- `app/Http/Controllers/SuperAdmin/PengaturanSistemController.php`
  - Tambah `updateLandingOffer()`.
- `routes/web.php`
  - Tambah route update.
- `resources/views/SuperAdmin/pengaturan-sistem/index.blade.php`
  - Tambah quick nav "Penawaran Landing".
- File baru:
  - `resources/views/SuperAdmin/pengaturan-sistem/_landing-penawaran.blade.php`
- Test baru:
  - `tests/Feature/LandingOfferSettingsTest.php`
- Update test:
  - `tests/Feature/LandingPageTest.php`

### Route

```php
Route::put('/pengaturan-sistem/landing-penawaran', [PengaturanSistemController::class, 'updateLandingOffer'])
    ->name('pengaturan-sistem.landing-penawaran.update');
```

### Validasi

- Field ID wajib untuk judul dan deskripsi.
- Field EN opsional; fallback ke ID.
- Semua field string dengan batas panjang yang wajar.
- CTA hanya label; link tetap ke route register/login.
- Tidak menerima HTML mentah; output Blade tetap escaped.

### Test

- [ ] Superadmin bisa menyimpan konten penawaran.
- [ ] Landing menampilkan konten dari setting.
- [ ] Landing memakai fallback saat setting kosong.
- [ ] Placeholder `{percent}` diganti komisi.
- [ ] Versi ID dan EN tampil sesuai locale.
- [ ] Validasi menolak judul/deskripsi ID kosong.
- [ ] User non-Superadmin tidak bisa mengakses route update.

### Commit

Satu file per commit, contoh:

```text
feat: tambah data penawaran landing di controller
feat: tampilkan penawaran landing dari setting
feat: tambah form penawaran landing superadmin
test: tambah test pengaturan penawaran landing
```

### Acceptance

Superadmin bisa mengubah teks section `/join#daftar` tanpa mengubah kode.

---

## 9. Fase 4 — Aktifkan Google Login

### Scope

Google OAuth aktif di halaman login. Tombol Google di halaman register dihapus untuk menghindari konflik role Owner/Customer.

### Alur login

- Klik "Masuk dengan Google".
- Redirect ke Google.
- Callback:
  - Cari user berdasarkan `google_id`.
  - Jika tidak ada, cari berdasarkan email.
  - Jika email ditemukan dan `google_id` kosong, link otomatis.
  - Jika email belum terdaftar, buat akun Customer otomatis.
  - Jika user nonaktif/suspend, tolak.
  - Jika email Google belum verified, tolak.
  - Jika email sudah terhubung ke `google_id` lain, tolak.
  - Jika user soft-deleted, tolak.
- Setelah sukses:
  - Regenerate session.
  - Handle area session jika multi-role aktif.
  - Redirect ke dashboard sesuai role.
  - Owner tanpa toko diarahkan ke pengajuan toko.

### Prasyarat eksternal

- [x] Buat OAuth Client ID tipe Web Application di Google Cloud Console.
- [ ] Authorized redirect URI (local sudah; production menunggu deployment):
  ```text
  http://localhost:8000/auth/google/callback
  https://nexprint.my.id/auth/google/callback
  ```
- [x] Simpan Client ID dan Client Secret.
- [x] Isi `.env` lokal (production nanti).

### File

- `composer.json`
- `composer.lock`
- `config/services.php`
- `.env.example`
- Migration baru:
  - `database/migrations/xxxx_xx_xx_xxxxxx_add_google_id_to_users_table.php`
- `app/Models/User.php`
- `app/Http/Controllers/Auth/GoogleController.php` (baru)
- `app/Support/PostLoginRedirector.php` (baru)
- `app/Http/Controllers/Auth/LoginController.php`
- `routes/web.php`
- `resources/views/customer/auth/login.blade.php`
- `resources/views/customer/auth/register.blade.php`
- `lang/id.json`
- `lang/en.json`
- `tests/Feature/GoogleAuthTest.php`

### Dependency

```bash
composer require laravel/socialite:^5.31
```

### Config

`config/services.php`:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

`.env.example`:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Migration

Tambahkan:

```php
$table->string('google_id')->nullable()->unique()->after('email');
```

Update `User::$fillable` dengan `google_id`.

### Route

```php
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');
```

### Helper PostLogin

Buat helper untuk menghindari duplikasi logika login:

- `session()->regenerate()`
- set cookie area jika `SessionArea::isEnabled()`
- handle redirect `intended`
- cek Owner tanpa toko
- redirect ke `EnsureRole::homeRouteFor()`

`LoginController@store` dan `GoogleController@callback` sama-sama memakai helper ini.

### UI

- Login page:
  - Tombol Google hanya muncul jika `config('services.google.client_id')` dan `client_secret` terisi.
  - Jika belum dikonfigurasi, tombol disembunyikan.
- Register page:
  - Hapus divider "or".
  - Hapus tombol "Sign up with Google".

### Error handling

Pesan yang perlu disiapkan:

- Login Google gagal.
- Email Google belum terverifikasi.
- Akun tidak aktif.
- Akun sudah terhubung dengan Google lain.
- Akun tidak ditemukan / tidak valid.

Jangan tampilkan detail exception ke user. Log detail ke `storage/logs/laravel.log`.

### Test

- [x] User dengan `google_id` bisa login.
- [x] User lama dengan email sama otomatis di-link.
- [x] Email baru otomatis dibuat sebagai Customer.
- [x] User nonaktif ditolak.
- [x] Email Google belum verified ditolak.
- [x] `google_id` berbeda ditolak.
- [x] User soft-deleted ditolak.
- [x] Callback error kembali ke login dengan pesan.
- [x] Tombol Google hidden jika env kosong.
- [x] Redirect Owner tanpa toko benar.
- [x] Session regenerate dan area session benar (helper bersama `LoginController`).

### Manual test

- [x] Isi `.env` dengan credentials asli.
- [x] Jalankan `php artisan config:clear`.
- [x] Buka `/login` dan pastikan tombol Google tampil (HTTP check).
- [ ] Klik tombol Google.
- [ ] Pilih akun.
- [ ] Pastikan masuk ke dashboard.
- [ ] Cek `users.google_id` terisi.
- [ ] Logout, login lagi via Google.
- [ ] Coba akun Google baru, harus jadi Customer.
- [ ] Coba akun nonaktif, harus ditolak.

### Commit

Satu file per commit, contoh:

```text
feat: tambah dependency socialite
feat: tambah konfigurasi google oauth
feat: tambah kolom google_id users
feat: tambah controller google login
feat: tambah tombol login google
test: tambah test google login
```

### Acceptance

Google Login berfungsi end-to-end dengan credentials Google Cloud yang valid.

---

## 9b. Fase 4b — Google Role Modal dan OTP Reset Password

### Scope

- Modal Google Login dengan dua pilihan: **Pemilik Bisnis** dan **Pelanggan**.
- Google baru + Owner dibuat langsung sebagai Owner tanpa toko, login, lalu diarahkan ke `owner.pengajuan-toko`.
- Google baru + Customer dibuat langsung sebagai Customer.
- Akun Google lama tetap memakai role tersimpan. Jika pilihan modal berbeda, login ditolak dengan pesan agar memilih role yang benar.
- Role internal (Admin/Gudang/Produksi/Superadmin) tidak diubah oleh intent modal.
- Lupa password memakai OTP 6 digit; link reset lama dihapus.
- OTP berlaku 10 menit, maksimal 5 percobaan, cooldown kirim ulang 60 detik, dan rate limit per email + IP.
- Komentar konfigurasi email dev/production ditambahkan di `config/mail.php` dan `.env.example`.

### Alur Google

1. User klik **Masuk dengan Google**.
2. Modal tampil; user memilih **Pemilik Bisnis** atau **Pelanggan**.
3. Intent disimpan di session (`oauth_role_intent`) saat redirect ke Google.
4. Callback:
   - Akun baru + Owner → buat Owner, login, redirect pengajuan toko.
   - Akun baru + Customer → buat Customer, login, redirect customer home.
   - Akun lama + intent cocok → login.
   - Akun lama + intent berbeda → tolak, kembali ke login dengan pesan.
   - Role internal → tetap login sesuai role tersimpan.
5. Tidak ada fitur upgrade Customer → Owner.

### Alur OTP

1. `GET /forgot-password` menampilkan form email.
2. `POST /forgot-password` mengirim OTP ke email terdaftar dan redirect ke `GET /forgot-password/otp`.
3. `GET /forgot-password/otp` menampilkan input OTP, password baru, konfirmasi, dan tombol kirim ulang.
4. `POST /forgot-password/otp` memverifikasi OTP lalu menyimpan password baru.
5. OTP disimpan sebagai hash di tabel `password_reset_otps`; OTP dihapus setelah reset berhasil.
6. Route `/reset-password/{token}` dan `/reset-password` dihapus.

### File

- `app/Http/Controllers/Auth/GoogleController.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Auth/ResetPasswordController.php` (dihapus)
- `app/Mail/ResetPasswordOtpMail.php` (baru)
- `app/Models/PasswordResetOtp.php` (baru)
- `database/migrations/2026_11_07_020000_create_password_reset_otps_table.php` (baru)
- `resources/views/customer/auth/login.blade.php`
- `resources/views/customer/auth/forgot-password.blade.php`
- `resources/views/customer/auth/reset-password.blade.php` (dihapus)
- `resources/views/emails/reset-password-otp.blade.php` (baru)
- `routes/web.php`
- `config/mail.php`
- `.env.example`
- `lang/en.json`
- `tests/Feature/GoogleAuthTest.php`
- `tests/Feature/PasswordResetTest.php`

### Test

- [x] Modal Google menampilkan pilihan Owner dan Customer.
- [x] Modal mempertahankan parameter `redirect`.
- [x] Google baru + Owner langsung dibuat Owner dan diarahkan ke pengajuan toko.
- [x] Google baru + Customer dibuat Customer.
- [x] Customer lama yang memilih Owner ditolak.
- [x] Owner lama yang memilih Customer ditolak.
- [x] Role internal tidak berubah oleh intent.
- [x] OTP dikirim dan disimpan sebagai hash.
- [x] Email tidak dikenal tetap memakai alur generik tanpa mengirim OTP.
- [x] OTP benar mengganti password dan menghapus record OTP.
- [x] OTP salah menambah attempts dan menolak reset.
- [x] OTP kedaluwarsa dan max attempts ditolak.
- [x] Password policy OTP diuji.
- [x] Cooldown kirim ulang OTP diuji.
- [x] Route reset link lama 404.

### Verifikasi targeted

```bash
php artisan test --filter=GoogleAuthTest
php artisan test --filter=PasswordResetTest
php artisan view:cache
```

Hasil: GoogleAuthTest 25 passed (178 assertions), PasswordResetTest 13 passed (63 assertions), Blade cache berhasil.

### Reset data dev

Setelah implementasi disetujui, jalankan di lokal:

```bash
php artisan migrate:fresh --seed
```

Ini menghapus data lokal dan membuat ulang role, Superadmin, serta akun demo. Jangan dijalankan di production.

### Acceptance

- Google Login menampilkan modal role dan membuat akun baru sesuai pilihan.
- Owner baru via Google langsung masuk ke pengajuan toko.
- Akun lama tidak berubah role.
- Lupa password memakai OTP dan link reset lama tidak tersedia.
- Semua test targeted pass.

---

## 9c. Fase 4c — Perbaikan State OAuth Google (host mismatch)

### Masalah

Login Google dari `http://127.0.0.1:8000` gagal dengan pesan **"Login Google gagal. Silakan coba lagi."** karena `GOOGLE_REDIRECT_URI` mengarah ke `http://localhost:8000/auth/google/callback`.

Cookie sesi terikat ke host. `state` OAuth yang disimpan di sesi `127.0.0.1` tidak terbaca oleh callback di `localhost`, sehingga Socialite melempar `InvalidStateException`. Percobaan kedua tampak berhasil karena redirect error membawa browser ke `localhost`.

### Perbaikan

- `GoogleController@redirect` mengarahkan request dari host yang berbeda ke origin `GOOGLE_REDIRECT_URI` **sebelum** menyimpan intent/state.
- Parameter `redirect` absolut ditulis ulang ke origin callback agar tujuan login tetap terjaga.
- `AreaSession` tidak memilih cookie area untuk path `auth/google/*`, sehingga handshake OAuth selalu memakai cookie sesi default yang sama dengan callback.
- `InvalidStateException` ditangani khusus dengan pesan **"Sesi login Google kedaluwarsa. Silakan coba lagi."**
- Log OAuth mencatat class exception, host, dan status `state` di session.
- `.env.example` mendokumentasikan aturan satu origin kanonik.

### File

- `app/Http/Controllers/Auth/GoogleController.php`
- `app/Http/Middleware/AreaSession.php`
- `lang/en.json`
- `.env.example`
- `tests/Feature/GoogleAuthTest.php`

### Test

- [x] Request OAuth dari host berbeda diarahkan ke origin callback.
- [x] Parameter redirect absolut ditulis ulang ke origin callback.
- [x] OAuth tidak memakai cookie area saat multi-role aktif.
- [x] `InvalidStateException` menghasilkan pesan sesi kedaluwarsa.

### Verifikasi targeted

```bash
php artisan test --filter=GoogleAuthTest
```

Hasil: 29 passed (198 assertions).

### Catatan produksi

- `APP_URL` dan `GOOGLE_REDIRECT_URI` harus sama-sama memakai `https://nexprint.my.id/...`.
- Daftarkan `https://nexprint.my.id/auth/google/callback` di Google Cloud Console.
- Paksa redirect `www.nexprint.my.id` dan `http://` ke `https://nexprint.my.id` di web server.
- Aktifkan trusted proxies jika aplikasi berada di belakang reverse proxy agar skema/host terdeteksi benar.

---

## 9d. Fase 4d — Welcome Notification MVP (In-App)

### Scope

Notifikasi selamat datang in-app untuk akun baru. Email undangan karyawan **tidak**
termasuk fase ini.

Alur wajib:

1. Register customer/owner.
2. Google user baru.
3. Owner menambah karyawan.
4. SuperAdmin membuat user.
5. Admin membuat customer.

Opsional/ditunda:

- Checkout guest auto-account (ditunda agar toast tidak mengganggu halaman pembayaran).
- Admin manual order auto-create customer (ditunda).
- Email undangan/aktivasi karyawan.
- Migration `welcome_seen_at` / `first_login_at`.

### Keputusan

| Topik | Keputusan |
|---|---|
| Tipe notifikasi | `Notification::TIPE_SISTEM` |
| Channel | In-app saja |
| Penerima | Akun yang baru dibuat |
| Owner saat menambah karyawan | Tetap dapat notifikasi terpisah |
| URL | `EnsureRole::homeRouteFor($user->role?->nama_role)` |
| Customer UI | Item Notifikasi di drawer + badge + welcome toast sekali per sesi |
| Duplikasi | Dicegah karena notifikasi dibuat hanya saat akun dibuat |
| Password | Tidak boleh muncul di pesan notifikasi |
| Kegagalan notifikasi | Tidak boleh menggagalkan pembuatan akun |
| Migration | Tidak ada migration baru |
| Deploy | Ditunda sampai Fase 5 selesai |

### Status

- [x] 4d.0 — Audit baseline dan konfirmasi file
- [x] 4d.1 — Service `sendWelcome`
- [x] 4d.2 — Hook register customer/owner
- [x] 4d.3 — Hook Google user baru
- [x] 4d.4 — Hook owner tambah karyawan
- [x] 4d.5 — Hook SuperAdmin/Admin user creation
- [x] 4d.6 — Customer drawer badge + welcome toast
- [x] 4d.7 — Fix URL notifikasi customer
- [x] 4d.8 — Translation ID/EN
- [x] 4d.9 — Feature tests
- [ ] 4d.10 — Manual QA (automated smoke pass; browser QA digabung ke Fase 5)
- [x] 4d.11 — Commit dan review

### Implementasi

#### Service

File: `app/Services/NotificationService.php`

- Tambah `sendWelcome(User $user, ?User $actor = null, ?Store $store = null): ?Notification`.
- Judul: `__('Welcome to :brand!', ['brand' => brand()])`.
- Pesan per role:
  - Customer: akun siap / dibuat oleh Admin.
  - Owner: akun siap, lengkapi pengajuan toko.
  - Admin/Produksi/Gudang: ditambahkan sebagai role di toko.
  - Super Admin: akun siap.
- URL: `route(EnsureRole::homeRouteFor($user->role?->nama_role))`.
- Bungkus `try/catch` + `Log::warning` agar akun tetap dibuat jika notifikasi gagal.
- Tambah `isWelcome(Notification $notification): bool` untuk mendeteksi welcome
  notification di customer drawer tanpa menambah tipe baru.

#### Hook pembuatan akun

- `app/Http/Controllers/Auth/RegisterController.php` — setelah `User::create`.
- `app/Http/Controllers/Auth/GoogleController.php` — hanya di `createUser()`.
- `app/Http/Controllers/Owner/KaryawanController.php` — ganti notifikasi karyawan
  lama dengan `sendWelcome($user, auth()->user(), OwnerContext::currentStore())`;
  notifikasi `fireSelf` owner tetap.
- `app/Http/Controllers/SuperAdmin/ManajemenPenggunaController.php` — ganti
  “Akun Dibuat” dengan `sendWelcome($user, auth()->user())`.
- `app/Http/Controllers/Admin/DataCustomerController.php` — ganti notifikasi yang
  memuat password default dengan `sendWelcome($customer, auth()->user())`.

#### Customer UI

- `app/Providers/AppServiceProvider.php` — daftarkan
  `customer._partials.drawer` ke `NotificationComposer`.
- `resources/views/customer/_partials/drawer.blade.php` — item Notifikasi di grup
  Akun + badge unread + include `partials.notification-popup` dan script welcome.
- `resources/views/customer/_partials/notification-script.blade.php` (baru) —
  tampilkan welcome toast sekali per sesi via `sessionStorage`.
- `resources/views/customer/notifications/index.blade.php` — gunakan
  `$n->url ?: route('customer.order-tracking')`.

#### Copy karyawan

- `resources/views/Owner/karyawan/index.blade.php` — hapus janji email aktivasi;
  jelaskan akun langsung aktif dan owner membagikan kredensial.

#### Translation

- `lang/id.json`
- `lang/en.json`

Key utama:

- `Welcome to :brand!`
- `Your account is ready. Start exploring the latest collection from our ateliers.`
- `Your business owner account is ready. Complete your store application from the dashboard.`
- `You have been added as :role at :store. Open your dashboard to get started.`
- `Your account has been created with the :role role. Open your dashboard to get started.`
- `Your account has been created by :actor. Please change your password from Account Settings.`
- `Your account is ready. Open your dashboard to get started.`

### Test

File: `tests/Feature/WelcomeNotificationTest.php`

Kasus:

- Register customer/owner membuat welcome notification dengan URL yang benar.
- Google user baru membuat welcome; Google existing tidak.
- Owner menambah karyawan: karyawan dapat welcome, owner dapat notifikasi terpisah.
- SuperAdmin membuat user: welcome dengan URL sesuai role.
- Admin membuat customer: welcome tanpa password default.
- Halaman notifikasi customer memakai `$notification->url`.
- Drawer customer memuat item Notifikasi + welcome toast.

Commands:

```bash
php artisan test --filter=WelcomeNotificationTest
php artisan test --filter=GoogleAuthTest
php artisan test --filter=PasswordResetTest
php artisan test --filter=OwnerLoginRedirectTest
```

Hasil targeted:

- `WelcomeNotificationTest`: 9 passed (43 assertions).
- `GoogleAuthTest`: 29 passed (198 assertions).
- `PasswordResetTest`: 19 passed (97 assertions).
- `OwnerLoginRedirectTest`: 7 passed (19 assertions).
- `ManajemenPenggunaDeleteTest`: 4 passed (18 assertions).
- `CustomerLoginConsistencyTest`: 1 failed karena fixture admin seeded tidak ada
  (`ModelNotFoundException`), bukan regresi welcome notification.

### Manual QA

- [ ] Register customer → login → badge notifikasi muncul.
- [ ] Register customer → welcome toast muncul sekali.
- [ ] Register owner → welcome muncul di dashboard Owner.
- [ ] Owner tanpa toko → welcome mengarah ke dashboard.
- [ ] Google new customer/owner → welcome muncul.
- [ ] Google existing user → tidak ada welcome baru.
- [ ] Owner tambah karyawan → karyawan dapat welcome; owner dapat notifikasi.
- [ ] Admin buat customer → welcome tidak memuat password.
- [ ] Customer drawer → item Notifikasi + badge benar.
- [ ] Klik welcome di halaman notifikasi → URL benar.
- [ ] Refresh/login ulang → tidak ada welcome baru.
- [ ] Tidak ada error di `storage/logs/laravel.log`.

### Commit

- Satu file per commit.
- `--no-verify`.
- Tidak push.
- Tidak commit `.env`.
- Tidak commit dua file pre-existing yang tidak terkait:
  - `database/migrations/2026_10_23_000001_seed_pengaturan_umum_defaults.php`
  - `tests/Feature/LandingPageTest.php`

### Acceptance

- [x] Welcome notification dibuat untuk semua alur wajib.
- [x] Tidak ada duplikasi saat login ulang.
- [x] Tidak ada password di notifikasi.
- [x] Customer melihat badge dan welcome toast.
- [x] URL notifikasi customer benar.
- [x] Translation ID/EN ditambahkan.
- [x] Test targeted pass.
- [ ] Manual QA selesai (browser QA digabung ke Fase 5).
- [x] Tidak ada migration baru.
- [x] Setelah selesai, lanjut **Fase 5 — Regression penuh**.

---

## 10. Fase 5 — Regression dan penutupan

### Test environment

Test suite project ini memakai database lokal dan beberapa test bergantung pada
fixture demo (`admin@raliva.test`, `customer@raliva.test`, dst). DB lokal saat
regression hanya punya 1 Super Admin, 4 Owner, 1 Gudang, 0 Admin, 0 Customer,
sehingga 148 test gagal `ModelNotFoundException` sebelum fixture disiapkan.

Solusi yang dipakai: database test terpisah, **bukan** database produksi.

```powershell
# Hanya untuk database test, jangan pernah untuk production.
$env:DB_DATABASE='Raliva_Fashion_Testing'
php artisan migrate:fresh --seed --force
php artisan db:seed --class=RalivaDemoSeeder --force
php artisan test
```

Catatan:

- `migrate:fresh` hanya dijalankan pada `Raliva_Fashion_Testing`.
- Database produksi tetap wajib `php artisan migrate --force`.
- `RalivaDemoSeeder` membuat fixture aktif Admin, Produksi, Gudang, Customer,
  store, produk, dan transaksi demo.
- DB lokal `Raliva_Fashion` tidak diubah oleh regression ini.

### Test

```bash
php artisan test --filter=LandingPageTest
php artisan test --filter=LandingOfferSettingsTest
php artisan test --filter=LocaleSwitcherTest
php artisan test --filter=GoogleAuthTest
php artisan test --filter=PasswordResetTest
php artisan test
```

Hasil full suite (database test terpisah, `RalivaDemoSeeder`):

- **339 passed, 4 skipped, 0 failed, 0 risky (1.615 assertions)**.
- Durasi: 206,72 detik.
- 4 skip adalah test scope yang butuh toko kedua:
  - `AdminQcTanggapanTest`
  - `GudangBahanTest`
  - `QcTabRincianGudangTest`
  - `ProduksiQcGudangTest`
- Perbaikan test pada Fase 5:
  - `BrandNameTest` — ekspektasi brand kapital dan favicon fallback
    `images/logo.svg`.
  - `OwnerKomplainTest` — tambah assertion agar tidak risky.

Hasil targeted:

- `WelcomeNotificationTest`: 9 passed (43 assertions).
- `GoogleAuthTest`: 29 passed (198 assertions).
- `PasswordResetTest`: 19 passed (97 assertions).
- `OwnerLoginRedirectTest`: 7 passed (19 assertions).
- `ManajemenPenggunaDeleteTest`: 4 passed (18 assertions).
- `LocaleSwitcherTest`: 5 passed (21 assertions).
- `BrandNameTest`: 7 passed (7 assertions).

### Manual checklist

Status: automated regression selesai; browser manual QA **belum** dijalankan.
Jangan deploy sebelum checklist ini selesai.

- [ ] Login white mode tanpa shadow.
- [ ] Landing default Indonesia.
- [ ] Login default Indonesia.
- [ ] Switch EN/ID berfungsi.
- [ ] Superadmin edit penawaran.
- [ ] `/join#daftar` menampilkan perubahan.
- [ ] Landing fallback saat setting kosong.
- [ ] Google login existing user.
- [ ] Google new Customer via modal.
- [ ] Google new Owner via modal langsung ke pengajuan toko.
- [ ] Google existing Owner memilih Customer ditolak dengan pesan.
- [ ] Google existing Customer memilih Owner ditolak dengan pesan.
- [ ] Google login inactive user ditolak.
- [ ] Lupa password mengirim OTP dan reset berhasil.
- [ ] OTP salah/kedaluwarsa/max attempts ditolak.
- [ ] Tidak ada error di `storage/logs/laravel.log`.

### Commit

```text
test: regression revisi login bahasa landing google
test: sesuaikan ekspektasi brand kapital dan favicon
test: beri assertion pada smoke test komplain owner
```

### Acceptance

- [x] Full automated suite: 339 passed, 4 skipped data-dependent, 0 failed.
- [x] Targeted auth/brand/welcome tests pass.
- [ ] Browser manual checklist selesai.
- [ ] Deploy production setelah manual QA dan approval.

---

## 11. File map ringkas

### Login shadow
- `resources/views/customer/auth/login.blade.php`

### Bahasa
- `.env` (lokal)
- `.env.example`
- `lang/id.json`
- `lang/id/landing.php`

### Landing penawaran
- `app/Http/Controllers/LandingController.php`
- `app/Http/Controllers/SuperAdmin/PengaturanSistemController.php`
- `routes/web.php`
- `resources/views/landing/index.blade.php`
- `resources/views/SuperAdmin/pengaturan-sistem/index.blade.php`
- `resources/views/SuperAdmin/pengaturan-sistem/_landing-penawaran.blade.php`
- `tests/Feature/LandingOfferSettingsTest.php`
- `tests/Feature/LandingPageTest.php`

### Google Login
- `composer.json`
- `composer.lock`
- `config/services.php`
- `.env.example`
- migration `add_google_id_to_users_table`
- `app/Models/User.php`
- `app/Http/Controllers/Auth/GoogleController.php`
- `app/Support/PostLogin.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `routes/web.php`
- `resources/views/customer/auth/login.blade.php`
- `resources/views/customer/auth/register.blade.php`
- `lang/id.json`
- `lang/en.json`
- `tests/Feature/GoogleLoginTest.php`

### Welcome Notification (Fase 4d)
- `app/Services/NotificationService.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/Auth/GoogleController.php`
- `app/Http/Controllers/Owner/KaryawanController.php`
- `app/Http/Controllers/SuperAdmin/ManajemenPenggunaController.php`
- `app/Http/Controllers/Admin/DataCustomerController.php`
- `app/Providers/AppServiceProvider.php`
- `resources/views/customer/_partials/drawer.blade.php`
- `resources/views/customer/_partials/notification-script.blade.php` (baru)
- `resources/views/customer/notifications/index.blade.php`
- `resources/views/Owner/karyawan/index.blade.php`
- `lang/id.json`
- `lang/en.json`
- `tests/Feature/WelcomeNotificationTest.php` (baru)

---

## 12. Test matrix

| Area | Test |
|---|---|
| Locale default | Incognito landing/login Indonesia |
| Locale switch | ID -> EN -> ID |
| Landing fallback | Kosongkan setting, landing tetap normal |
| Landing edit | Superadmin ubah teks, landing berubah |
| Landing percent | `{percent}` diganti komisi |
| Google existing | Login via google_id |
| Google link | Email lama otomatis ter-link |
| Google new Customer | Email baru + intent Customer jadi Customer |
| Google new Owner | Email baru + intent Owner jadi Owner dan diarahkan ke pengajuan |
| Google role mismatch | Akun lama dengan intent berbeda ditolak |
| Google inactive | Ditolak |
| Google unverified | Ditolak |
| Google mismatch | Ditolak |
| Google hidden | Env kosong, tombol tidak tampil |
| Auth redirect | Owner tanpa toko diarahkan ke pengajuan |
| OTP send | OTP dikirim dan disimpan sebagai hash |
| OTP reset | OTP benar mengganti password dan menghapus record |
| OTP invalid | OTP salah/kedaluwarsa/max attempts ditolak |
| Reset link lama | Route lama 404 |
| Welcome register | Customer/Owner baru dapat welcome notification |
| Welcome Google | User baru dapat welcome; user lama tidak |
| Welcome karyawan | Karyawan dapat welcome; owner dapat notifikasi terpisah |
| Welcome SuperAdmin/Admin | User/customer baru dapat welcome tanpa password |
| Welcome customer UI | Drawer item, badge, toast sekali per sesi |
| Welcome URL | Halaman notifikasi customer memakai `$notification->url` |

---

## 13. Open items / yang perlu disiapkan

- [x] Google Cloud OAuth Client ID dan Secret.
- [ ] Authorized redirect URI production `https://nexprint.my.id/auth/google/callback` saat deploy.
- [x] Konfirmasi bahwa akun Google baru memakai pilihan modal Owner/Customer.
- [x] Konfirmasi bahwa tombol Google di register dihapus.
- [x] Konfirmasi shadow login dihapus total, bukan hanya outer shadow.
- [ ] Konfigurasi provider email production agar OTP benar-benar terkirim.
- [ ] Verifikasi browser: modal Google, akun Owner baru, dan alur OTP.
- [ ] Verifikasi browser Fase 4d: badge + welcome toast customer, welcome karyawan, dan URL notifikasi.
- [x] Database test terpisah `Raliva_Fashion_Testing` disiapkan dan di-seed untuk regression.
- [ ] Browser manual QA Fase 5 (login, landing, Google, OTP, welcome).

---

## 14. Definition of done

- [x] Empat revisi selesai di kode dan automated test.
- [x] Semua test targeted pass.
- [x] Full test pass (339 passed, 4 skipped data-dependent, 0 failed).
- [ ] Manual checklist selesai.
- [x] Commit rapi, satu file per commit.
- [x] Tidak push.
- [x] Tidak menyentuh hosting.
- [x] Rencana deployment diperbarui setelah revisi selesai.

---

## 15. Deployment

Ditunda. Jangan mengerjakan deploy di fase ini.

Setelah revisi selesai dan manual QA disetujui:

- Backup database production terlebih dahulu.
- Build ulang paket deploy.
- Pastikan vendor lengkap.
- Update `.env` production (`APP_LOCALE=id`, Google credentials, SMTP/Resend OTP).
- Jalankan `php artisan migrate --force` di production.
- **Jangan pernah** menjalankan `migrate:fresh` di production.
- Fase 4d welcome notification tidak menambah migration baru.
- Bersihkan cache yang relevan (`config:clear`, `route:clear`, `view:clear`).
- Verifikasi upload dan storage.
- Verifikasi Google callback persis `https://nexprint.my.id/auth/google/callback`.
- Verifikasi SMTP port 465 memakai `MAIL_SCHEME=smtps`.
- Verifikasi brand `NEXPRINT` di tab/browser dan UI.
- Verifikasi welcome notification customer/owner/karyawan setelah deploy.

---

## 16. Catatan eksekusi

Plan ini sudah dieksekusi sampai Fase 5 automated regression.

Status terakhir:

1. Fase 0-4d selesai di kode dan automated test.
2. Fase 5 automated regression: 339 passed, 4 skipped data-dependent, 0 failed.
3. Browser manual QA dan deployment production masih pending.
4. Semua commit belum di-push.
5. Working tree masih menyisakan dua file pre-existing yang tidak terkait:
   - `database/migrations/2026_10_23_000001_seed_pengaturan_umum_defaults.php`
   - `tests/Feature/LandingPageTest.php`
