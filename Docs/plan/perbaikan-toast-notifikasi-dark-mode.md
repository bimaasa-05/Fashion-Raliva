# Perbaikan Toast & Popup Notifikasi di Dark Mode

Tanggal: 2026-10-23
Status: selesai dieksekusi; verifikasi otomatis lulus, cek visual browser pending.
Lokasi final repo: `Docs/plan/perbaikan-toast-notifikasi-dark-mode.md`.

## Latar belakang

Setelah SuperAdmin menyimpan nama platform, muncul toast sukses. Di dark mode
toast tersebut tampil putih terang sehingga tidak mengikuti tema. Masalah serupa
juga ada pada popup notifikasi real-time.

## Temuan akar masalah

- Toast flash memakai markup di:
  - `resources/views/partials/ui-scripts.blade.php` (SuperAdmin, Admin, Gudang)
  - `resources/views/layouts/owner.blade.php` (Owner)
  - `resources/views/layouts/produksi.blade.php` (Produksi)
- Ketiganya memakai class `bg-inverse-surface text-inverse-on-surface`.
- Di `resources/views/partials/theme-head.blade.php`, `.dark` mendefinisikan:
  - `--c-inverse-surface: 238 236 236` (terang)
  - `--c-inverse-on-surface: 30 30 30` (gelap)
  Jadi toast memang sengaja dibalik menjadi putih di dark mode.
- `resources/views/partials/notification-popup.blade.php` membuat elemen
  dinamis dengan `bg-white` hardcoded, sehingga popup juga putih di dark mode.
- Dropdown lonceng `partials/notification-panel.blade.php` sudah memakai
  `bg-surface-container-lowest`; bukan penyebab dan tidak perlu diubah.

## Keputusan yang diusulkan

- **Light mode tidak diubah**: toast tetap gelap agar kontras dengan halaman
  terang (perilaku sekarang).
- **Dark mode**: toast memakai surface gelap tema:
  - background `--c-sc-highest` (`#333333`)
  - teks `--c-on-surface` (`#F0EEEE`)
  - border `--c-outline-variant` (`#2E2E2E`)
- Override toast ditempatkan terpusat di `theme-head.blade.php` dengan selector
  `.dark #raliva-toast`, sehingga otomatis berlaku untuk ketiga markup toast
  (SuperAdmin, Admin, Gudang, Owner, Produksi) tanpa mengedit layout satu per satu.
- Popup notifikasi: ganti `bg-white` menjadi `bg-surface-container-lowest`
  agar light tetap putih dan dark menjadi `#141414`, dengan border yang sudah ada.
- Tidak ada migrasi baru. Tidak ada perubahan database.

## Rencana implementasi

### Langkah 0 — Dokumen rencana

- Salin dokumen ini ke `Docs/plan/perbaikan-toast-notifikasi-dark-mode.md`.
- Commit terpisah sebelum menyentuh kode:
  `chore: tambah dokumen rencana toast dark mode`

### Langkah 1 — Toast flash theme-aware

File: `resources/views/partials/theme-head.blade.php`

Tambahkan setelah blok override aksen dark:

```css
/* Toast flash: jangan pakai inverse-surface di dark (hasilnya putih). */
.dark #raliva-toast {
    background-color: rgb(var(--c-sc-highest)) !important;
    color: rgb(var(--c-on-surface)) !important;
    border: 1px solid rgb(var(--c-outline-variant));
}
```

- Light mode tetap memakai `bg-inverse-surface` (toast gelap).
- Dark mode memakai surface gelap + border.
- Selector ID menang atas utility class, jadi tidak perlu mengubah 3 markup toast.

Commit: `fix: sesuaikan toast flash dengan tema dark`

### Langkah 2 — Popup notifikasi theme-aware

File: `resources/views/partials/notification-popup.blade.php`

Pada pembuatan elemen dinamis, ganti:

```text
bg-white
```

menjadi:

```text
bg-surface-container-lowest
```

Biarkan `border border-muted-border`, `text-on-surface`, dan shadow yang ada.
Hasil: light tetap putih, dark mengikuti surface gelap.

Commit: `fix: sesuaikan popup notifikasi dengan tema dark`

### Langkah 3 — Tes regresi

File baru: `tests/Feature/ToastThemeTest.php`

Cakupan:
- `theme-head.blade.php` memuat rule `.dark #raliva-toast` dan token surface.
- `notification-popup.blade.php` tidak lagi memakai `bg-white` dan memakai
  `bg-surface-container-lowest`.
- Markup toast `id="raliva-toast"` ada di `ui-scripts`, layout Owner, dan
  layout Produksi (memastikan override CSS menjangkau semua role).
- Render dashboard SuperAdmin memuat elemen toast.

Commit: `test: tema toast dan popup notifikasi`

### Langkah 4 — Verifikasi

- `php artisan view:cache` lalu `php artisan view:clear`.
- `php artisan test --filter="ToastThemeTest|PengaturanSistemBrandingTest|ProfileSmokeTest"`.
- `git diff --check`; cek working tree.
- Verifikasi visual manual:
  - Dark mode SuperAdmin → simpan nama platform → toast gelap, bukan putih.
  - Dark mode Owner/Admin/Produksi/Gudang → toast tetap terbaca.
  - Popup notifikasi real-time di dark mode tidak putih.
  - Light mode toast tetap gelap dan kontras.
- Update checklist + log progres dokumen, commit terpisah.
- Tidak push.

## Catatan working tree (penting)

Saat rencana ini dibuat, ada perubahan **belum di-commit** yang bukan bagian
dari perbaikan ini:

- `database/migrations/2026_10_23_000001_seed_pengaturan_umum_defaults.php`
  berubah dari `Raliva`/`support@raliva.com` menjadi `Nexprint`/`support@nexprint.com`.

Perubahan itu adalah pekerjaan user dan **tidak akan disentuh, di-revert, atau
di-commit** tanpa konfirmasi. Saat ini DB berisi `nama_platform = "NexPrint"` dan
`email_support = "support@raliva.com"`, jadi ada perbedaan dengan isi migrasi.
Perlu keputusan terpisah apakah migrasi seed ingin diselaraskan.

## Commit yang dieksekusi

1. `chore: tambah dokumen rencana toast dark mode` — `d8e69b5a`
2. `fix: sesuaikan toast flash dengan tema dark` — `811de768`
3. `fix: sesuaikan popup notifikasi dengan tema dark` — `c3fbd7c6`
4. `test: tema toast dan popup notifikasi` — `5a6b8e41`
5. `chore: catat progres perbaikan toast` — commit dokumen ini

Aturan tetap: 1 file = 1 commit, `--no-verify`, tanpa push.

## Hasil verifikasi

- `php artisan test --filter=ToastThemeTest` — 4 passed (11 assertions).
- `php artisan test --filter="PengaturanSistemBrandingTest|ProfileSmokeTest"` —
  16 passed (75 assertions).
- `php artisan view:cache` + `view:clear` — OK.
- `git diff --check` — bersih.
- Verifikasi visual browser dark/light belum dijalankan di sesi ini.

## Log progres

| Tanggal | Commit | Langkah | Status |
|---|---|---|---|
| 2026-10-23 | `d8e69b5a` | Dokumen rencana | ✅ |
| 2026-10-23 | `811de768` | Toast flash | ✅ |
| 2026-10-23 | `c3fbd7c6` | Popup notifikasi | ✅ |
| 2026-10-23 | `5a6b8e41` | Tes & verifikasi | ✅ (visual browser pending) |
