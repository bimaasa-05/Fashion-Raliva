# Revisi SuperAdmin — Pengaturan Umum, Dark Mode, Hapus User, Bahasa

Tanggal keputusan: 2026-10-23.
Status dokumen: sedang dieksekusi.
Lokasi final di repo: `Docs/plan/revisi-superadmin-pengaturan-darkmode-hapus-user-bahasa.md`.

## Latar belakang

- Nominal/harga di dark mode memakai `gold-accent` yang di-hardcode `#8B1E3F`
  (maroon gelap) dan gradient `#6D1428 → #8B1E3F` — kontras rendah di background gelap.
- Hapus user dari SuperAdmin menghasilkan 500 karena `$user->delete()` (hard delete)
  menabrak banyak FK `restrictOnDelete` (`checkouts.user_id`, `stores.owner_id`,
  `payments.verifier_id`, `activity_logs.user_id`, wallet, reviews, complaints, dll).
- Kartu login memakai `.frame-gold` dengan outer shadow hitam yang terlalu kuat di light mode.
- Pemilih bahasa baru ada di halaman Profil semua role; user meminta penempatan yang
  lebih mudah diakses (sidebar vs navbar).
- Pengaturan platform SuperAdmin sudah tersimpan di tabel `settings` (key-value).
  User meminta migrasi baru untuk pengaturan **GENERAL (Umum)**.

## Keputusan yang dikunci

- **Sumber pengaturan platform:** tabel `settings` yang sudah ada (key-value).
  **Tidak ada tabel baru.** Migrasi baru hanya **seed default GENERAL** yang idempoten
  (`insertOrIgnore`), tidak menimpa nilai yang sudah diubah SuperAdmin.
- **Dark mode:** token aksen global.
  - `gold-accent` jadi CSS variable `--c-accent`: light `#8B1E3F`, dark `#FF5A5A` (merah cerah).
  - `.dark .text-gradient-gold` override gradient merah cerah dengan `!important`
    (satu tempat di `partials/theme-head.blade.php`, specificity lebih tinggi dari
    definisi per-layout).
  - Berlaku semua role; ikon/border aksen ikut merah cerah di dark (disepakati).
- **Hapus user:** soft delete (`users.deleted_at` + `SoftDeletes`).
  - Riwayat transaksi/audit tetap utuh; user hilang dari daftar & tidak bisa login.
  - SuperAdmin tidak bisa menghapus akun sendiri.
  - Email tetap terpakai oleh akun terhapus (unique index); anonimisasi email ditunda.
- **Login:** outer shadow `.frame-gold` dikecilkan **hanya di light mode**; dark tetap.
- **Bahasa:** pemilih di **navbar/topbar**, bukan sidebar.
  - Route existing `customer.locale.switch` (POST, session) dipakai ulang — tanpa migrasi.
  - Radio bahasa di halaman Profil tetap dipertahankan.
  - Persistensi per akun (`users.locale`) **ditunda** (bisa menyusul).
- **Aturan kerja:** 1 file = 1 commit, `--no-verify`, tanpa push.
  Migrasi dijalankan di DB dev `Raliva_Fashion` sebelum tes.

## Konfirmasi (2026-10-23)

- **GENERAL:** seed default 4 key yang sudah ada — `nama_platform`,
  `email_support`, `whatsapp_support`, `mode_maintenance`. **Tidak ada field baru.**
  - Default: `nama_platform` = `Raliva`, `email_support` = `support@raliva.com`
    (sama dengan fallback controller saat ini).
- **Switch bahasa:** ditempatkan di **topbar/navbar**; radio di halaman Profil tetap.

## Paket 1 — Migrasi seed GENERAL

- [x] Migrasi `database/migrations/2026_10_23_000001_seed_pengaturan_umum_defaults.php`.
  - `insertOrIgnore` key: `nama_platform`, `email_support`, `whatsapp_support`, `mode_maintenance`.
  - `down()` no-op (jangan hapus setting yang mungkin sudah diedit admin).
- [x] Verifikasi: `php artisan migrate`, cek baris `settings`, buka halaman SuperAdmin.

Commit: `feat: seed default pengaturan umum superadmin` (`8563354c`)

## Paket 2 — Dark mode nominal

- [x] `resources/views/partials/theme-head.blade.php`:
  - `"gold-accent": "rgb(var(--c-accent) / <alpha-value>)"`.
  - `:root { --c-accent: 139 30 63; }` dan `.dark { --c-accent: 255 90 90; }`.
  - `.dark .text-gradient-gold { ... !important; }` gradient merah cerah.
  - Tambahan hasil audit: `.dark .text-gold-accent` karena nominal di Owner/
    Admin/Produksi/Gudang memakai class ini dan di-hardcode maroon di tiap layout.
- [ ] Verifikasi visual light/dark di SuperAdmin, Owner, Admin, Produksi, Gudang.

Commit: `feat: aksen dan nominal merah cerah di dark mode` (`264fec88`)

## Paket 3 — Soft delete user

- [x] Migrasi `2026_10_23_000002_add_soft_deletes_to_users_table.php` (`softDeletes`).
- [x] `app/Models/User.php`: gunakan `SoftDeletes`.
- [x] `SuperAdmin\ManajemenPenggunaController@destroy`: guard tidak bisa hapus akun sendiri.
- [x] `Customer\SettingsController@deleteAccount`: soft delete + logout
      (hapus cascade manual tidak diperlukan).
- [x] `SuperAdmin/manajemen-pengguna/index.blade.php`: copy modal
      "dihapus dari sistem; riwayat transaksi tetap tersimpan".
- [x] Tes baru `tests/Feature/ManajemenPenggunaDeleteTest.php` (4 tes, 18 assertions):
  - Owner punya toko → hapus sukses, toko tetap ada.
  - Admin yang pernah verifikasi pembayaran → hapus sukses, payment tetap ada.
  - Customer punya checkout → self-delete sukses, checkout tetap ada.

Commit:
- `feat: tambah kolom soft delete pengguna`
- `feat: aktifkan soft deletes pada model user`
- `fix: cegah superadmin menghapus akun sendiri`
- `fix: hapus akun customer pakai soft delete`
- `fix: perjelas konfirmasi hapus pengguna`
- `test: hapus pengguna dengan relasi tetap berhasil`

## Paket 4 — Login shadow

- [ ] `resources/views/customer/auth/login.blade.php`: `.frame-gold` light
      `inset glow + 0 4px 12px -10px rgba(0,0,0,.05)`; dark tetap
      `0 12px 24px -8px rgba(0,0,0,.18)`.

Commit: `fix: kurangi shadow kartu login di light mode`

## Paket 5 — Pemilih bahasa di topbar

- [ ] Partial baru `resources/views/partials/locale-switcher.blade.php`
      (tombol `ID | EN`, form POST `customer.locale.switch`, tanpa JS berat).
- [ ] Pasang di topbar 5 layout (mobile + desktop):
      `superadmin`, `owner`, `admin`, `produksi`, `gudang`.
- [ ] Tes `tests/Feature/LocaleSwitcherTest.php`: POST locale → session berubah;
      halaman topbar memuat switcher.
- [ ] Tambah label di `lang/en.json` bila diperlukan.

Commit:
- `feat: partial pemilih bahasa`
- `feat: pasang pemilih bahasa di topbar superadmin`
- `feat: pasang pemilih bahasa di topbar owner`
- `feat: pasang pemilih bahasa di topbar admin`
- `feat: pasang pemilih bahasa di topbar produksi`
- `feat: pasang pemilih bahasa di topbar gudang`
- `test: pemilih bahasa mengubah locale sesi`

## Paket 6 — Verifikasi & penutupan

- [ ] `php artisan migrate` (2 migrasi baru).
- [ ] `php artisan view:cache && php artisan view:clear`.
- [ ] `php artisan test --filter "ManajemenPenggunaDelete|LocaleSwitcher"` + regresi terkait.
- [ ] `php artisan test --filter "AdminLaporan"` (regresi laporan admin).
- [ ] `git diff --check`; cek working tree bersih.
- [ ] Update checklist + log progres dokumen ini, commit terpisah.
- [ ] Verifikasi manual browser: light/dark semua role, hapus user, switcher bahasa.

## Log progres

| Tanggal | Commit | Paket | Status |
|---|---|---|---|
| 2026-10-23 | `da4a5a13` | Dokumen rencana dibuat | ✅ |
| 2026-10-23 | `8563354c` | Paket 1 — seed GENERAL | ✅ |
| 2026-10-23 | `264fec88` | Paket 2 — dark mode | ✅ |
| 2026-10-23 | `0644bbf5`–`f1b2ffd0` | Paket 3 — soft delete | ✅ |
| 2026-10-23 | - | Paket 4 — login shadow | ⏳ |
| 2026-10-23 | - | Paket 5 — pemilih bahasa | ⏳ |
| 2026-10-23 | - | Paket 6 — verifikasi | ⏳ |

## Catatan risiko

- Perubahan token `gold-accent` juga mengubah warna ikon, border, dan link aksen
  di dark mode (bukan hanya teks nominal) — sudah disepakati.
- Chart/JS dengan warna hardcoded tidak otomatis ikut; hanya diubah jika terlihat
  bermasalah saat verifikasi visual.
- Email akun terhapus tetap unik; registrasi ulang dengan email yang sama akan
  ditolak sampai fitur anonimisasi email dibuat.
- Cakupan terjemahan `lang/en.json` perlu dicek sebelum switcher dipromosikan;
  string yang belum diterjemahkan akan tampil campur.
- `php artisan migrate` wajib dijalankan sebelum tes karena tes memakai DB dev nyata
  (`DatabaseTransactions`, bukan `RefreshDatabase`).
