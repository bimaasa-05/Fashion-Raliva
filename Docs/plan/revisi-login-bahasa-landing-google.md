# Revisi Login, Bahasa, Landing Penawaran, dan Google Login

Tanggal dibuat: 2026-11-06  
Status: draft plan, belum dieksekusi  
Repo: `C:\laragon\www\Raliva-Fashion`  
Branch: `main`  
Baseline commit: `a1726835`  
Working tree saat plan dibuat: clean

Dokumen ini adalah **satu file plan** untuk seluruh pekerjaan. Semua fase, file, test, dan acceptance criteria ada di sini. Jangan dipecah menjadi file plan terpisah.

---

## 1. Tujuan

Menyelesaikan empat revisi sebelum kembali ke rencana deployment hosting:

1. Menghilangkan shadow pada border card login di white mode.
2. Menjadikan Bahasa Indonesia sebagai bahasa default website.
3. Membuat konten section penawaran di landing page `/join#daftar` bisa diedit dari dashboard Superadmin.
4. Mengaktifkan Google Login yang benar-benar berfungsi.

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
| Akun Google baru | Otomatis dibuat sebagai Customer |
| Owner via Google | Tidak otomatis; Owner tetap daftar manual |
| Eksekusi | Satu fase per checkpoint, tidak sekaligus |
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

- [ ] Buat OAuth Client ID tipe Web Application di Google Cloud Console.
- [ ] Authorized redirect URI:
  ```text
  http://localhost:8000/auth/google/callback
  https://nexprint.my.id/auth/google/callback
  ```
- [ ] Simpan Client ID dan Client Secret.
- [ ] Isi `.env` lokal dan production nanti.

### File

- `composer.json`
- `composer.lock`
- `config/services.php`
- `.env.example`
- Migration baru:
  - `database/migrations/xxxx_xx_xx_xxxxxx_add_google_id_to_users_table.php`
- `app/Models/User.php`
- `app/Http/Controllers/Auth/GoogleController.php` (baru)
- `app/Support/PostLogin.php` (baru)
- `app/Http/Controllers/Auth/LoginController.php`
- `routes/web.php`
- `resources/views/customer/auth/login.blade.php`
- `resources/views/customer/auth/register.blade.php`
- `lang/id.json`
- `lang/en.json`
- `tests/Feature/GoogleLoginTest.php`

### Dependency

```bash
composer require laravel/socialite
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

- [ ] User dengan `google_id` bisa login.
- [ ] User lama dengan email sama otomatis di-link.
- [ ] Email baru otomatis dibuat sebagai Customer.
- [ ] User nonaktif ditolak.
- [ ] Email Google belum verified ditolak.
- [ ] `google_id` berbeda ditolak.
- [ ] User soft-deleted ditolak.
- [ ] Callback error kembali ke login dengan pesan.
- [ ] Tombol Google hidden jika env kosong.
- [ ] Redirect Owner tanpa toko benar.
- [ ] Session regenerate dan area session benar.

### Manual test

- [ ] Isi `.env` dengan credentials asli.
- [ ] Jalankan `php artisan config:clear`.
- [ ] Buka `/login`.
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

## 10. Fase 5 — Regression dan penutupan

### Test

```bash
php artisan test --filter=LandingPageTest
php artisan test --filter=LandingOfferSettingsTest
php artisan test --filter=LocaleSwitcherTest
php artisan test --filter=GoogleLoginTest
php artisan test
```

### Manual checklist

- [ ] Login white mode tanpa shadow.
- [ ] Landing default Indonesia.
- [ ] Login default Indonesia.
- [ ] Switch EN/ID berfungsi.
- [ ] Superadmin edit penawaran.
- [ ] `/join#daftar` menampilkan perubahan.
- [ ] Landing fallback saat setting kosong.
- [ ] Google login existing user.
- [ ] Google login new Customer.
- [ ] Google login Owner existing.
- [ ] Google login inactive user ditolak.
- [ ] Tidak ada error di `storage/logs/laravel.log`.

### Commit

```text
test: regression revisi login bahasa landing google
```

### Acceptance

Semua test pass dan manual checklist selesai.

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
| Google new | Email baru jadi Customer |
| Google inactive | Ditolak |
| Google unverified | Ditolak |
| Google mismatch | Ditolak |
| Google hidden | Env kosong, tombol tidak tampil |
| Auth redirect | Owner tanpa toko diarahkan ke pengajuan |

---

## 13. Open items / yang perlu disiapkan

- [ ] Google Cloud OAuth Client ID dan Secret.
- [ ] Authorized redirect URI lokal dan production.
- [ ] Konfirmasi bahwa akun Google baru otomatis menjadi Customer.
- [ ] Konfirmasi bahwa tombol Google di register dihapus.
- [ ] Konfirmasi shadow login dihapus total, bukan hanya outer shadow.

---

## 14. Definition of done

- [ ] Empat revisi selesai.
- [ ] Semua test targeted pass.
- [ ] Full test pass.
- [ ] Manual checklist selesai.
- [ ] Commit rapi, satu file per commit.
- [ ] Tidak push.
- [ ] Tidak menyentuh hosting.
- [ ] Rencana deployment diperbarui setelah revisi selesai.

---

## 15. Deployment

Ditunda. Jangan mengerjakan deploy di fase ini.

Setelah revisi selesai:

- Build ulang paket deploy.
- Pastikan vendor lengkap.
- Update `.env` production (`APP_LOCALE=id`, Google credentials).
- Jalankan migration `google_id` di production.
- Import/export DB sesuai rencana deployment terpisah.
- Verifikasi upload dan storage.

---

## 16. Catatan eksekusi

Plan ini dibuat sebagai draft. Belum ada file aplikasi yang diubah.

Saat masuk mode implementasi:

1. Mulai dari Fase 0.
2. Berhenti setelah setiap fase.
3. Jangan lanjut tanpa konfirmasi.
