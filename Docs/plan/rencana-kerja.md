# Rencana Kerja — Auth, Profil, Multi-Sesi & Refund Karyawan

Dokumen gabungan seluruh workstream yang sedang dikerjakan di Raliva-Fashion.
Tanggal dibuat: 2026-09-17. Semua fakta (baris kode) terverifikasi pada tanggal tersebut.

---

## 0. Daftar Workstream

| # | Workstream | Status | Prioritas |
|---|---|---|---|
| WS1 | Autentikasi (login/logout/register) | Audit selesai | Tinggi |
| WS2 | Profil semua role (6 role) | Batch A & B selesai + lulus `ProfileSmokeTest` | Tinggi |
| WS3 | Lupa & Reset Password | Batch C selesai + lulus `PasswordResetTest` | Tinggi |
| WS4 | Keamanan auth | Batch D selesai + lulus `AuthSecurityTest` | Sedang |
| WS5 | Multi-sesi dev 6 role (1 Chrome) | Implementasi selesai + lulus `SessionAreaTest` & `MultiRoleSessionTest` | Tinggi |
| WS6 | Perbaikan refund pada Rekap Karyawan | Ditunda | Sedang |
| WS7 | Komplain/Refund (dokumen) | Sudah didokumentasikan | Referensi |

---

## WS1 — Autentikasi (Login/Logout/Register)

### Kondisi saat ini (terverifikasi)

- Route terpusat di `routes/web.php:108-115`:
  - `GET /login` → `LoginController::create`
  - `POST /login` → `LoginController::store`
  - `GET /register` → `RegisterController::create`
  - `POST /register` → `RegisterController::store`
  - `POST /logout` → `LoginController::destroy` (middleware `auth`)
- `LoginController::store` (`app/Http/Controllers/Auth/LoginController.php:21-52`):
  validasi `email` + `password` → `Auth::attempt` → cek `status === aktif` /
  `User::STATUS_AKTIF` → `session()->regenerate()` → redirect `redirect` (jika safe)
  atau `intended(route(EnsureRole::homeRouteFor(role)))`.
- `destroy` (`:54-62`): `Auth::logout()` + `session()->invalidate()` +
  `session()->regenerateToken()` → redirect `route('login')`.
- `RegisterController::store` (`:26-55`): validasi `nama_lengkap`, `email unique`,
  `password min:8|confirmed`, `role in customer|owner`, `terms accepted`.
  Role Owner langsung redirect ke login (belum ada toko). `email_verified_at` di-set.
- **Belum ada rate-limit (`throttle`) pada route login.** Tidak ada limit khusus reset password.

### Pekerjaan

1. Tambah rate-limit pada `POST /login` (`routes/web.php:110`), mis. `throttle:5,1` atau
   limiter kustom berdasarkan email+IP.
2. Seragamkan pesan error dan redirect pasca-login di semua role.
3. Sesuaikan logout dengan area cookie (terintegrasi dengan WS5).
4. Tambahkan uji dasar: login sukses / gagal / akun nonaktif.

### Kriteria terima

- Login berkali-kali gagal kena limit (429) dengan pesan jelas.
- Login berhasil mengarah ke dashboard sesuai role.
- Logout membersihkan sesi yang benar (lihat WS5).

---

## WS2 — Profil Semua Role

### Matriks kondisi saat ini (terverifikasi)

| Role | Controller | Field password | telp max | gender/DOB | foto di form | remove_photo | Lokasi foto |
|---|---|---|---|---|---|---|---|
| Customer | `Customer\ProfileController` | `current_password` / `new_password` | 30 | ✅ | ✅ | ✅ | `public/profil` |
| Admin (dipakai) | `Admin\ProfilController` | `password_lama` / `password` | **20** | ❌ | ❌ (route terpisah) | ❌ | `Disk public` (`profil`) |
| Admin (dead) | `Admin\AdminProfilController` | `password_lama` / `password` | **20** | ❌ | ❌ | ❌ | `avatars` |
| Owner | `Owner\ProfilController` | `password_lama` / `password` | **20** | ❌ | ✅ | ❌ | `public/profil` |
| Gudang | `Gudang\ProfilController` | `password_lama` / `password_baru` | 30 | ❌ | ✅ | ❌ | `public/profil` |
| SuperAdmin | `SuperAdmin\ProfilController` | `password_lama` / `password_baru` | 30 | ❌ | ✅ | ❌ | `public/profil` |
| Produksi | `Produksi\ProfilController` (stub) | — | — | ❌ | ❌ | ❌ | — |

Route profil staff: superadmin `web.php:304-306`, admin `390-393`, gudang `414-416`,
owner `497-500`, produksi `524`. Customer: `web.php:175,181`.

Catatan penting:

- `routes/web.php:14` me-*alias* `Admin\ProfilController as AdminProfilController`.
  Artinya route admin memakai `Admin\ProfilController` (98 baris). `Admin\AdminProfilController`
  (78 baris, simpan foto ke `avatars/`, pakai `back()+'success'`) adalah **dead code**.
- `Produksi\ProfilController` hanya 13 baris (`index()` return view) → masih stub dengan
  data hardcoded di view `resources/views/Produksi/profil/index.blade.php`.

### Inkonsistensi yang diseragamkan

1. Nama field password: saat ini campur
   (`current_password`/`new_password`, `password_lama`/`password`, `password_lama`/`password_baru`).
2. `nomor_telepon` max 20 (Admin/Owner) vs 30 (Customer/Gudang/SuperAdmin).
3. `gender` & `tanggal_lahir` hanya tersedia di Customer.
4. `remove_photo` hanya tersedia di Customer.
5. Lokasi simpan foto campur: `public/profil`, `Disk public 'profil'`, `avatars/`.
6. `Admin\AdminProfilController` dead code.

### Pekerjaan — Batch A (fondasi bersama)

1. Buat `app/Http/Requests/Profile/UpdateProfileRequest.php` — validasi + pesan seragam:
   `nama_lengkap required|max:150`, `email required|email|max:150|unique:users,email,{id},user_id`,
   `nomor_telepon nullable|max:30`, `gender nullable|in:male,female`,
   `tanggal_lahir nullable|date|before:today`, `foto_profil nullable|image|max:2048`.
2. Buat `app/Http/Requests/Profile/UpdatePasswordRequest.php` — field seragam:
   `password_lama`, `password_baru`, `password_baru_confirmation` dengan skema WS4.
3. Buat helper `App\Support\ProfilePhoto` (atau trait) — simpan ke `public/profil`
   dengan prefix role, hapus file lama saat ganti/removal, dukung `remove_photo`.
4. Standarkan field password di seluruh controller → `password_lama`/`password_baru`.
5. `nomor_telepon` max **30** untuk semua role.
6. Tambahkan `gender` + `tanggal_lahir` untuk semua role (staff + customer) —
   **tanpa migrasi**: `User::$fillable` sudah memuat `gender`, `tanggal_lahir`, `foto_profil`
   (`app/Models/User.php:26-37`) dan `casts` sudah `date` (`:44-51`).
7. Tambah `remove_photo` untuk semua role.
8. Hapus `app/Http/Controllers/Admin/AdminProfilController.php` dan bersihkan alias
   `routes/web.php:14`.

### Pekerjaan — Batch B (Produksi)

9. Lengkapi `Produksi\ProfilController`: `index()`, `updateProfile()`, `updatePassword()`
   (ikuti pola SuperAdmin/Gudang + `ActivityLogger` + `Notification::fireSelf`).
10. Tambah route `PUT/POST /produksi/profil` dan `/produksi/profil/password` (`web.php:524`).
11. Ganti `resources/views/Produksi/profil/index.blade.php` dari data hardcoded →
    data `Auth::user()` + form update/ganti foto/ganti password nyata.

### Kriteria terima

- Perilaku & pesan identik di 6 role: ubah nama/email/telp/gender/DOB/foto/password.
- Foto tampil konsisten lewat `User::getFotoProfilUrlAttribute()` (`User.php:58-74`).
- Tidak ada controller profil duplikat/dead.

### Status implementasi (2026-09-17)

- Batch A & B **selesai**. Diverifikasi `tests/Feature/ProfileSmokeTest.php` (3 test, 35 assertion):
  render halaman profil 5 role staff, simpan gender/DOB/telp, tolak password lama salah.
- Catatan verb update: Gudang memakai `POST /profil`; role lain `PUT` (Sudah sesuai view masing-masing).
- Catatan test: middleware `AuthenticateSession` (`bootstrap/app.php:16`) menyimpan `password_hash`
  di sesi; berpindah role via `actingAs()` dalam satu test perlu `flushSession()` dulu.
- `pint` tidak bisa dijalankan di env ini (butuh PHP ^8.3; mesin ini 8.2) → andalkan `php -l`.

---

## WS3 — Lupa & Reset Password

### Kondisi saat ini (terverifikasi)

- `routes/web.php:114-115` hanya closure `fn() => view(...)` — **tidak ada logika**.
- `resources/views/customer/auth/forgot-password.blade.php` (444 baris) &
  `reset-password.blade.php` (291 baris): form murni simulasi di JS (`setTimeout`),
  tidak mengirim email/token apa pun.
- Tabel `password_reset_tokens` **sudah ada** (`database/migrations/0001_01_01_000000_create_users_table.php:27`).
- Broker `users` aktif: `config/auth.php:95-102` → expire 60 menit, throttle 60 detik.
- `User` **belum** memakai `Illuminate\Auth\Passwords\CanResetPassword`.
- `.env` `MAIL_MAILER=log` → rilis email masuk ke `storage/logs/laravel.log`.

### Pekerjaan — Batch C

1. Tambahkan `use Illuminate\Auth\Passwords\CanResetPassword;` pada `app/Models/User.php`.
2. Buat `app/Http/Controllers/Auth/ForgotPasswordController.php`:
   `showLinkRequestForm()`, `sendResetLinkEmail()` via `Password::sendResetLink()`,
   dengan validasi email + pesan status.
3. Buat `app/Http/Controllers/Auth/ResetPasswordController.php`:
   `showResetForm($token)` (render halaman reset dengan token+token di body),
   `reset(Request)` via `Password::reset()` (validasi skema WS4), lalu redirect login.
4. Ubah `web.php:114-115`: 
   - `GET /forgot-password` → `ForgotPasswordController@showLinkRequestForm` (`password.request`)
   - `POST /forgot-password` → `sendResetLinkEmail` (`password.email`)
   - `GET /reset-password/{token}` → `showResetForm` (`password.reset`)
   - `POST /reset-password` → `reset` (`password.update`)
5. Rombak kedua view agar benar-benar submit: `@csrf`, tampilkan error/sukses dari server,
   isi `action` + field `token` (hidden) & `email`.
6. Konfigurasi mail dev (tetap `log` sampai SMTP nyata); verifikasi token kadaluarsa &
   throttle dikerjakan.

### Kriteria terima

- Request reset membuat baris di `password_reset_tokens` + email ke log.
- Link reset valid dapat mengganti password dan langsung login dengan password baru.
- Token invalid/kadaluarsa ditolak; token tidak bisa dipakai ulang.

### Status implementasi (2026-09-17)

- Batch C **selesai**. Diverifikasi `tests/Feature/PasswordResetTest.php` (6 test, 19 assertion).
- **Koreksi audit**: `User` tidak perlu menambah `CanResetPassword` — parent
  `Illuminate\Foundation\Auth\User` sudah memakainya (`vendor/.../Foundation/Auth/User.php:19`),
  dan `Password::sendResetLink()` terbukti bekerja.
- Controller final: `ForgotPasswordController` (`showLinkRequestForm`, `sendResetLinkEmail`) &
  `ResetPasswordController` (`showResetForm`, `reset`). Field reset tetap `password`/`password_confirmation`
  (konvensi broker Laravel), bukan `password_baru`.
- `sendResetLinkEmail` membalas pesan generik (`status`) untuk mencegah enumerasi akun.
- Reset sukses → redirect `login` + flash `success` (sesuai tampilan banner di `login.blade.php`),
  token langsung dihapus (sekali pakai). Throttle `throttle:6,1` pada kedua POST.
- Kedua view dirombak: `@csrf`, `method=POST`, `action` nyata, `@error`, hidden `token`+`email`;
  simulasi `setTimeout` dihapus.

---

## WS4 — Keamanan Auth

### Pekerjaan — Batch D

1. Rate-limit login (WS1) + rate-limit request reset password.
2. Kebijakan password terpadu di profil/register/reset:
   `required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed`
   (Customer profil sudah punya pola ini; staff belum).
3. Pastikan `AuthenticateSession` (`bootstrap/app.php:16`) aktif & konsisten
   setelah penggantian password.
4. Regenerasi sesi saat kejadian auth penting (sudah di login; tambahkan saat reset).

### Keputusan terbuka

- Kebijakan password final (kandidat: min 8 + minimal 1 kapital + 1 angka).
- Setelah ganti password: apakah sesi perangkat lain dipaksa logout ("logout other devices")
  atau dibiarkan seperti sekarang (refresh login as-is)?

### Status implementasi (2026-09-17)

- Batch D **selesai** (inti). Diverifikasi `tests/Feature/AuthSecurityTest.php` (3 test, 20 assertion).
- **Rate-limit login** memakai limiter bernama `login` (`AppServiceProvider::boot`),
  kunci = `email|IP`, 5x/menit, respons `withErrors(['email' => 'Terlalu banyak percobaan login...'])`.
  Route `POST /login` → `->middleware('throttle:login')`.
- **Rate-limit reset** → `throttle:6,1` pada `POST /forgot-password` & `POST /reset-password`.
- **Kebijakan password final diterapkan**: `min:8` + `regex:/[A-Z]/` + `regex:/[0-9]/` + `confirmed`
  di profil (Batch A), register (`RegisterController`), dan reset (Batch C).
- **Sesi setelah ganti password** — ternyata sudah aman: `AuthenticateSession` menyimpan ulang
  `password_hash_{guard}` **setelah** response (`vendor/.../AuthenticateSession.php:85-89`), sehingga
  device saat ini tetap login dan device lain (hash lama) otomatis ter-logout saat request berikutnya.
  Terverifikasi test `test_changing_password_keeps_current_session_authenticated`.
- **Keputusan "logout other devices"**: perilaku yang berlaku = device saat ini dipertahankan,
  device lain logout otomatis (efek `AuthenticateSession`). Tidak perlu `Auth::logoutOtherDevices()`.
- Regenerasi sesi saat login sudah ada (`LoginController.php:44`); reset password tidak login
  sehingga regenerasi tidak relevan.

---

## WS5 — Multi-Sesi Dev 6 Role (1 Chrome)

### Akar masalah (terverifikasi)

- Satu cookie sesi `{app}_session`, path `/`, driver database (`config/session.php:130-133`),
  diterima semua tab browser.
- Login sukses memanggil `session()->regenerate()` (`LoginController.php:44`) → menimpa
  sesi seluruh window: login Owner di tab 2 membuat tab 1 (SA) ikut menjadi Owner.
- Akibatnya fitur SA di tab 1 diblokir (middleware `role:Super Admin` gagal) dan tombol
  "Kembali ke Beranda" di `errors/access-denied.blade.php` mengarah ke dashboard Owner
  karena `homeRouteFor` diambil dari user aktif (`EnsureRole.php:24-26`, `:32-43`).
- Ini bukan bug role/middleware, tetapi batas desain session tunggal.

### Desain (dev-only, toggle `.env`)

Satu cookie per area role:

| Area (prefix URL) | Role | Cookie |
|---|---|---|
| `/superadmin` | Super Admin | `raliva_superadmin_session` |
| `/owner` | Owner | `raliva_owner_session` |
| `/admin` | Admin | `raliva_admin_session` |
| `/gudang` | Gudang | `raliva_gudang_session` |
| `/produksi` | Produksi | `raliva_produksi_session` |
| `/customer`, `/`, `/login`, dll | Customer/publik | cookie default (tidak berubah) |

Verifikasi teknis yang sudah dilakukan:

- `$middleware->web(prepend: [...])` didukung (`vendor/.../Configuration/Middleware.php:327`)
  dan prepend di-merge **sebelum** `StartSession` (`Middleware.php:518-522`),
  sehingga middleware bisa mengubah `config('session.cookie')` tepat waktu.
- CSRF aman: semua AJAX memakai `<meta name="csrf-token">` per halaman
  (`partials/theme-head.blade.php:1`), bukan cookie `XSRF-TOKEN` bersama
  (di beberapa halaman ada `{{ csrf_token() }}` langsung di JS, juga per-sesi).
- Satu-satunya endpoint lintas-area adalah `/notifikasi/*` (`web.php:531-536`)
  yang dipanggil dari `partials/layout-scripts.blade.php:217,244` dan
  `partials/notification-panel.blade.php:70-72` → diatasi via fallback **Referer**.
- Logout global (1 form bersama di `partials/profile-menu.blade.php:44`) → diatasi
  dengan input tersembunyi `session_area`.

### Pekerjaan

1. `config/session.php`: tambah `'multi_role' => env('SESSION_MULTI_ROLE', false)`.
2. `.env` + `.env.example`: `SESSION_MULTI_ROLE=true` (hanya development).
   **Hapus baris ini → fitur mati, perilaku kembali normal.**
3. Buat `app/Support/SessionArea.php`: peta `role → area`, `areaForPath()`,
   `cookieNameForArea()` — satu sumber kebenaran.
4. Buat `app/Http/Middleware/AreaSession.php`: bila flag aktif, resolve area via
   (a) segment pertama path (staff prefix),
   (b) input `session_area` (khusus logout),
   (c) fallback parsial dari header `Referer` (khusus `/notifikasi/*`);
   lalu `config(['session.cookie' => 'raliva_'.$area.'_session'])`.
5. `bootstrap/app.php`: `$middleware->web(prepend: [\App\Http\Middleware\AreaSession::class])`.
6. `LoginController::store`: setelah auth sukses + `regenerate()`, bila flag aktif dan role
   punya area → set `config(['session.cookie' => ...])` sebelum return redirect;
   **abaikan `remember` saat flag aktif** (cookie `remember_web_*` namanya sama antar-area).
7. `partials/profile-menu.blade.php`: tambah hidden `session_area` sesuai role user aktif.

Tidak ada perubahan DB/migrasi.

### Kriteria terima

- 6 tab berbeda-role login bersamaan tanpa saling menimpa.
- Fitur SA normal; `errors/access-denied` + "Kembali ke Beranda" mengarah benar.
- Notifikasi (badge polling, aktivitas, mark-all-read, mark-read) jalan di tiap area.
- Logout di satu area hanya mengakhiri area itu; tab lain tetap login.
- `SESSION_MULTI_ROLE` dihapus → kembali ke single-session (perilaku lama).

### Batasan

- Remember-me nonaktif saat flag multi-sesi aktif.
- Sesi `database` tersimpan beberapa baris (1 per area) saat testing — wajar, dibersihkan GC.
- Setelah ubah `.env`: jalankan `php artisan config:clear`.

### Status implementasi (2026-09-17)

- **Selesai + teruji.** File baru: `app/Support/SessionArea.php` (peta role→area, `areaForPath()`,
  `cookieNameForArea()`), `app/Http/Middleware/AreaSession.php` (resolve area via segment path →
  input `session_area` → Referer; set `config('session.cookie')`), `bootstrap/app.php`
  `$middleware->web(prepend: [AreaSession::class])`, `config/session.php` key `multi_role`.
- `LoginController::store`: saat flag aktif → abaikan `remember` dan ganti nama cookie sesi
  (`config` + `$request->session()->setName()`) sesuai area role; `partials/profile-menu.blade.php`
  memuat hidden `session_area` untuk logout yang benar.
- **Deviasi dari rencana**: `.env` = `SESSION_MULTI_ROLE=true` (dev), tetapi `.env.example` = `false`
  (rencana awal `true`). `false` lebih aman karena contoh env tidak akan mengaktifkan multi-sesi
  tanpa sadar di deployment.
- **Pengujian otomatis**:
  - `tests/Unit/SessionAreaTest.php` — 5 test: mapping role↔area, path→area, fallback Referer,
    nama cookie per area, default nonaktif.
  - `tests/Feature/MultiRoleSessionTest.php` — 3 test (11 assertion): login staff (Owner) men-set
    `raliva_owner_session`; login customer tetap cookie default; flag-off → cookie default semua role.
  - `phpunit.xml` ditambah `SESSION_MULTI_ROLE=false` agar suite berjalan deterministik single-session.
- **Gotcha test**: `actingAs()` membuat session guard (dan store) lebih awal dengan nama cookie
  default, sehingga assertion cookie area harus memakai alur login sungguhan (POST `/login`),
  bukan `actingAs`.
- **Catatan pemakaian dev**: login staff lewat `/login` bersama memakai cookie default — jika
  customer login dulu, login staff akan menggantikan sesi customer. Urutan aman: login staff
  dulu, customer terakhir (atau role berbeda di browser profile berbeda).
- QA manual browser (6 tab + notifikasi + logout per area + flag-off) masih perlu dijalankan sesuai
  kriteria terima di atas; verifikasi teknis (prepend sebelum `StartSession`, Referer, meta CSRF)
  sudah tercatat di bagian Desain.

---

## WS6 — Perbaikan Refund pada Rekap Karyawan (DITUNDA)

Dokumen detail: [`refund-rekap-karyawan.md`](./refund-rekap-karyawan.md).

Keputusan yang sudah disepakati (tercatat di dokumen tersebut):

1. Refund dihitung ke rekap karyawan hanya saat status **`selesai`**.
2. Refund `escalated`: `reviewed_by` harus tetap admin yang menangani/mengeskalasi;
   Owner saat `setujui`/`tolak` **tidak boleh** menimpanya.

### Pekerjaan

1. Sesuaikan layanan rekap karyawan (filter refund `selesai`; atribusi via `reviewed_by`).
2. Tambahkan guard agar `Owner\PengembalianDanaController` tidak menimpa
   `reviewed_by` untuk refund `escalated`.
3. **Tidak menyentuh** logika customer/produksi, status refund, wallet saldo,
   maupun sistem komplain.

---

## WS7 — Komplain/Refund (Referensi)

Dokumen yang sudah ada di `Docs/plan`:

- `roadmap-komplain-refund.md`
- `alur-komplain-lengkap.md`
- `alur-refund-dan-saldo.md`
- `integrasi-komplain-refund.md`
- `refund-rekap-karyawan.md`

Temuan terbuka (belum dikerjakan):

- Refund Admin belum di-scope `AdminContext::assignedStoreIds()`.
- `Order::STATUS_REFUND = 'refund'` ada tapi tidak terpakai.
- Tabel `refunds` belum memiliki kolom `complaint_id`.
- Hanya SuperAdmin yang dapat menutup komplain (`tutup`).

---

## Urutan Eksekusi & Verifikasi

- Batch A (fondasi profil) → Batch B (Produksi) → Batch C (reset password) →
  Batch D (keamanan) → WS5 (multi-sesi) → WS6 (refund, ditunda).
- Setiap batch: `php artisan config:clear`, QA manual per role, `vendor\bin\pint` (format).
- WS5 wajib QA 6 tab + notifikasi + logout + pengujian flag-off.

---

## Keputusan yang Masih Terbuka

1. Kebijakan password final (kandidat: min 8 + 1 kapital + 1 angka).
2. Setelah ganti password: logout semua perangkat lain atau tidak?
3. (Opsional) Konfigurasi mail nyata (SMTP) untuk reset password — saat ini `MAIL_MAILER=log`.