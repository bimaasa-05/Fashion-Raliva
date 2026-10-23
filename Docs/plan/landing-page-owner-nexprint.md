# Landing Page Owner NexPrint

Tanggal: 2026-10-23
Status: selesai dieksekusi; verifikasi otomatis lulus, cek visual browser pending.
Lokasi final repo: `Docs/plan/landing-page-owner-nexprint.md`.

## Tujuan

Membuat pintu masuk publik yang fokus ke pemilik brand/calon Owner, tanpa
mengganti home marketplace customer yang sudah ada.

## Keputusan

- Brand final: **NexPrint** (wordmark teks dulu, tanpa file logo baru).
- Landing utama di `/` untuk calon Owner.
- `/customer` tetap menjadi home marketplace untuk pembeli.
- Halaman penjelasan dipisah:
  - `/untuk-brand` = kenapa NexPrint, manfaat, dan fitur.
  - `/buka-toko` = cara bergabung, persyaratan, FAQ, dan CTA daftar.
- Jika nanti digabung, `/untuk-brand` dapat menjadi halaman kanonik dengan
  section `#buka-toko`, lalu `/buka-toko` diarahkan ke anchor tersebut.
- Navbar landing memuat switch bahasa ID/EN, toggle tema, tautan
  **Saya Pembeli** ke `/customer`, dan CTA **Buka Toko**.
- Kontak support memakai setting: email, WhatsApp, dan Instagram.
  Instagram memakai setting baru `instagram_support`; kontak hanya dirender
  jika nilainya terisi.
- Owner yang belum punya toko atau tokonya ditolak diarahkan ke
  `/owner/pengajuan-toko` setelah login. Owner dengan toko aktif tetap ke
  dashboard; toko nonaktif tetap ke dashboard (mode terkunci).
- Tidak ada perubahan pada home customer, alur checkout, atau data transaksi.

## Fakta pencairan Owner

Hasil analisa kode:

- `Owner\PencairanDanaController` tidak menghitung fee.
- Tabel `withdrawals` tidak punya kolom `fee`/`jumlah_bersih`.
- SuperAdmin memindahkan dana tepat sebesar `jumlah` saat menyetujui dan
  menandai dibayar.
- Komisi platform default 5% sudah dipotong saat order selesai melalui
  `WalletService::creditOrder`, bukan saat pencairan.
- `biaya_penarikan_saldo` hanya dipakai untuk penarikan saldo Customer.

Konsekuensi copy landing: jangan menulis "tanpa komisi". Gunakan
"pendaftaran gratis" dan jelaskan komisi platform yang berlaku.

## Halaman dan komponen

### Route

- `GET /` → `LandingController@index`, name `landing`.
- `GET /untuk-brand` → `LandingController@brand`, name `landing.brand`.
- `GET /buka-toko` → `LandingController@openStore`, name `landing.open-store`.

### View

- `resources/views/landing/layout.blade.php`
- `resources/views/landing/_partials/header.blade.php`
- `resources/views/landing/_partials/footer.blade.php`
- `resources/views/landing/index.blade.php`
- `resources/views/landing/brand.blade.php`
- `resources/views/landing/open-store.blade.php`

### Terjemahan

- `lang/en/landing.php`
- `lang/id/landing.php`

### Setting kontak

- Tambah `Setting::INSTAGRAM_SUPPORT`.
- Tambah input Instagram di halaman Pengaturan Sistem.
- Tampilkan email/WhatsApp/Instagram secara kondisional di footer landing.
- Sinkronkan email support di DB existing ke `support@nexprint.com`
  melalui migrasi data yang hanya mengubah nilai lama.

## Desain

- Bahasa visual: editorial fashion, Playfair Display + Manrope, aksen burgundy,
  latar warm ivory, dan dark mode mengikuti `theme-dark`/`raliva-theme`.
- Hero landing: copy Owner + mockup dashboard operasional (CSS/HTML, bukan
  aset foto baru).
- Foto editorial memakai aset yang sudah ada di
  `public/assets/picture/home-pictures/`.
- Responsif: menu mobile, CTA tetap terlihat, tidak ada horizontal scroll.
- Aksesibilitas: skip link, label tombol, focus state, `prefers-reduced-motion`.

## Rencana implementasi dan commit

1. Dokumen rencana.
2. Controller landing.
3. Route halaman utama.
4. Layout landing.
5. Partial header.
6. Partial footer/kontak.
7. Halaman landing utama.
8. Halaman Untuk Brand.
9. Halaman Buka Toko.
10. Setting Instagram di model.
11. Setting Instagram di controller SuperAdmin.
12. Input Instagram di halaman pengaturan.
13. Redirect Owner tanpa toko.
14. Tes landing.
15. Tes redirect Owner.
16. Penyesuaian tes halaman utama.
17. Migrasi default identitas NexPrint (file yang sudah diubah user).
18. Migrasi sinkron email support existing.
19. Terjemahan Inggris.
20. Terjemahan Indonesia.

Setiap file di-commit terpisah dengan `git commit --no-verify` dan pesan
satu baris berbahasa Indonesia. Tidak ada push.

## Verifikasi

- `php artisan migrate`
- Tes memakai database dev `Raliva_Fashion` dengan `DatabaseTransactions`.
- `php artisan test --filter=LandingPageTest`
- `php artisan test --filter=OwnerLoginRedirectTest`
- `php artisan test --filter=ExampleTest`
- `php artisan view:cache` lalu `php artisan view:clear`
- `git diff --check`
- Cek manual: landing light/dark, mobile, switch bahasa, tautan CTA, dan
  kontak support.

## Hasil verifikasi

- `LandingPageTest`: 6 lulus, 27 assertion.
- `OwnerLoginRedirectTest`: 5 lulus, 13 assertion.
- `ExampleTest`: 2 lulus, 4 assertion.
- `PengaturanSistemBrandingTest`: 11 lulus, 32 assertion.
- `LocaleSwitcherTest`: 4 lulus, 17 assertion.
- `php artisan migrate` berhasil menjalankan migrasi sinkron email support.
- Smoke test HTTP: `/`, `/untuk-brand`, `/buka-toko`, dan `/customer`
  mengembalikan status 200.
- Data dev: `nama_platform=NexPrint`, `email_support=support@nexprint.com`,
  `instagram_support` kosong sampai diisi SuperAdmin.
- `php artisan view:cache` berhasil; `view:clear` dijalankan setelah verifikasi.
- Cek visual browser untuk light/dark dan mobile masih pending.

## Konsolidasi v2 — satu halaman Owner

Tanggal: 2026-10-24
Status: disetujui; implementasi dimulai.

### Keputusan

- `/` menjadi satu-satunya halaman landing Owner dengan anchor
  `#beranda`, `#tentang`, `#fitur`, `#buka-toko`, `#faq`, dan `#kontak`.
- `/customer` tetap menjadi home marketplace pembeli. Prioritas root tetap
  Owner selama akuisisi Owner menjadi fokus.
- `/untuk-brand` diarahkan permanen (301) ke `/#tentang`.
- `/buka-toko` diarahkan permanen (301) ke `/#buka-toko`.
- Nama route lama `landing.brand` dan `landing.open-store` dipertahankan
  sebagai route redirect agar tautan lama tidak rusak.
- View `landing/brand.blade.php` dan `landing/open-store.blade.php` dihapus
  setelah isinya digabung ke `landing/index.blade.php`.
- Navbar: Beranda, Tentang, Fitur, Buka Toko, FAQ, Kontak. Sisi kanan:
  **Belanja** sebagai pill menonjol, switch ID/EN, toggle tema,
  Masuk/Dashboard, dan CTA Daftar Owner.
- Footer memakai anchor yang sama, menonjolkan Belanja, dan tetap
  menampilkan kontak secara kondisional.
- Tidak ada klaim "tanpa komisi". Komisi platform tetap dijelaskan 5%
  dan dipotong saat pesanan selesai.

### Section hasil merge

1. Hero `#beranda`: copy Owner, mockup dashboard, CTA Owner, dan tautan
   Belanja yang terlihat.
2. Trust strip: verifikasi, dashboard, produksi, laporan.
3. `#tentang`: alasan NexPrint, audiens brand, dan kutipan editorial.
4. `#fitur`: delapan fitur operasional.
5. `#buka-toko`: empat langkah, persyaratan, alur setelah daftar, dan CTA
   daftar Owner.
6. `#faq`: enam pertanyaan termasuk komisi platform dan pencairan.
7. `#kontak`: email/WhatsApp/Instagram hanya dirender jika setting terisi;
   fallback ke Pusat Bantuan.

### Verifikasi

- Test redirect 301 untuk dua URL lama.
- Test anchor, CTA Belanja, CTA daftar, komisi 5%, kontak kondisional, dan
  switch bahasa.
- `php artisan view:cache` lalu `php artisan view:clear`.
- `git diff --check`.
- Cek manual light/dark dan mobile.
- Setiap file di-commit terpisah dengan `git commit --no-verify`; tidak push.
