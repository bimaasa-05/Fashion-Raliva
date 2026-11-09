# Perbaikan Dashboard & Sidebar Semua Role

Tanggal: 2026-11-09
Status: rencana eksekusi; belum selesai sampai verifikasi otomatis dan QA browser lulus.
Lokasi final repo: `Docs/plan/perbaikan-dashboard-sidebar-superadmin.md`.

## Latar belakang

Dashboard SuperAdmin menampilkan tiga gauge "Kinerja Operasional" dengan angka
100% padahal data platform masih kosong: 0 pelanggan, 1 toko, 0 pesanan, dan
16 produk. Sidebar juga terlihat tidak lengkap karena hanya grup yang sedang
aktif yang otomatis terbuka.

Masalah serupa ditemukan di dashboard role lain: Gudang memakai fallback 100%
saat denominator nol, Admin menampilkan progress minimal 4% untuk tugas 0,
Owner selalu menampilkan badge "Terverifikasi" dan logo placeholder,
Produksi masih memakai label lokasi hardcoded, serta beberapa identitas toko
memakai logo bawaan platform atau foto produk.

## Akar masalah

- `resources/views/partials/ui-scripts.blade.php`: `ralivaRenderDonut`
  menghitung `total = jumlah seluruh segmen`. Gauge mengirim segmen
  `[nilai, 100 - nilai]`, sehingga bagian tengah selalu menampilkan 100.
- `SuperAdmin/DashboardController.php`: omzet bulan ini dibanding bulan lalu
  memakai `100%` saat bulan lalu nol dan bulan ini ada omzet. Nilai ini tidak
  punya pembanding yang sah.
- `Gudang/DashboardController.php`: `akurasi.pct` dan `sla.pct` diisi 100
  ketika tidak ada varian atau tidak ada pesanan. Fallback tanpa gudang juga
  mengirim 100.
- `Admin/dashboard-operasional/index.blade.php`: progress bar memakai
  `max(4, $tugas['pct'])`, sehingga tugas 0 tetap terlihat 4%.
- Sidebar semua role memakai `$isActive` sebagai satu-satunya penentu grup
  terbuka. Di halaman dashboard hanya grup "Utama" yang terbuka.
- `Owner/dashboard/index.blade.php`: badge header hardcoded "Terverifikasi",
  logo memakai `images/logo.svg`, dan progress aktivitas memakai angka tetap.
- `Admin/dashboard-operasional/index.blade.php`: identitas toko memakai
  `images/logo.svg`; rating hanya dihitung dari toko pertama.
- `Produksi/dashboard/index.blade.php`: label "Atelier Aktif",
  "Atelier Produksi Raliva", dan "Workshop Kemang" tidak berasal dari data.
- Fallback logo toko di halaman customer memakai `product_photo_url()`,
  sehingga logo toko kosong tampil sebagai foto produk.

## Keputusan

- Gauge tanpa data menampilkan **"&mdash;"** dengan label **"Belum Ada Data"**.
- Omzet tanpa pembanding menampilkan **"&mdash;"** dengan label
  **"Belum Ada Pembanding"**; ini berbeda dari "Belum Ada Data".
- Sidebar semua role: semua grup **terbuka secara default**, tetap bisa
  ditutup manual. Grup aktif tetap ditandai lewat item aktif.
- Logo toko tanpa unggahan memakai ikon **storefront**, bukan foto produk
  atau logo platform.
- Password akun demo lokal:
  - 6 karyawan demo Owner: `password`
  - SuperAdmin demo lokal: `password`
  - Production tetap memakai password kuat dan tidak menjalankan seeder demo.
- Tidak mengubah migration, konfigurasi production, atau `.env` pada batch ini.

## Ruang lingkup P0

1. Komponen donut:
   - Tambah `data-donut-center-value` untuk nilai tengah eksplisit.
   - Tambah `data-donut-center-text` untuk label tengah eksplisit.
   - Nilai tengah gauge memakai capaian, bukan total segmen.
2. SuperAdmin:
   - Gauge omzet, kepuasan, dan SLA memakai capaian yang benar.
   - Tampilkan "&mdash;" + label yang sesuai saat data tidak ada.
3. Gudang:
   - `akurasi` dan `sla` tidak lagi default 100.
   - Fallback tanpa gudang mengirim status tanpa data.
   - Gauge menampilkan "&mdash;" + "Belum Ada Data" saat denominator nol.
4. Admin:
   - Progress tugas 0 tampil 0%, bukan minimal 4%.
5. Sidebar:
   - Semua grup default terbuka untuk SuperAdmin, Admin, Owner, Gudang,
     dan Produksi.
   - Tombol grup tetap bisa collapse dan `aria-expanded` konsisten.

## Ruang lingkup P1

6. Owner:
   - Badge header mengikuti status toko.
   - Logo toko memakai `photo_url()` dan fallback ikon storefront.
   - Persentase aktivitas palsu dihapus; status aktivitas ditampilkan apa adanya.
7. Admin:
   - Logo toko memakai `photo_url()` dan fallback ikon storefront.
   - Rating dihitung dari seluruh toko dalam scope, bukan hanya toko pertama.
   - Identitas toko menunjukkan jumlah toko jika scope lebih dari satu.
8. Produksi:
   - Nama atelier, badge, dan status memakai data toko yang ditugaskan.
   - Label "Workshop Kemang" dihapus.
9. Customer:
   - Fallback logo toko di beranda dan halaman toko yang diikuti memakai
     ikon storefront.
10. Data demo:
    - `OwnerSeeder` membuat/menugaskan 6 karyawan demo:
      Admin, Produksi, Produksi 2, Gudang, Gudang 2, Gudang 3.
    - Semua memakai password `password` untuk local/demo.
    - `SuperAdminSeeder` memakai password `password` untuk local/demo.

## File yang akan diubah

- `resources/views/partials/ui-scripts.blade.php`
- `app/Http/Controllers/SuperAdmin/DashboardController.php`
- `resources/views/SuperAdmin/dashboard.blade.php`
- `app/Http/Controllers/Gudang/DashboardController.php`
- `resources/views/Gudang/dashboard/index.blade.php`
- `resources/views/Admin/dashboard-operasional/index.blade.php`
- `app/Http/Controllers/Admin/DashboardOperasionalController.php`
- `resources/views/partials/sidebar-menu.blade.php`
- `resources/views/partials/sidebar-menu-admin.blade.php`
- `resources/views/partials/sidebar-menu-owner.blade.php`
- `resources/views/partials/sidebar-menu-gudang.blade.php`
- `resources/views/partials/sidebar-menu-produksi.blade.php`
- `resources/views/Owner/dashboard/index.blade.php`
- `app/Http/Controllers/Owner/DashboardController.php`
- `resources/views/Produksi/dashboard/index.blade.php`
- `app/Http/Controllers/Produksi/DashboardController.php`
- `resources/views/customer/home/index.blade.php`
- `resources/views/customer/followed-stores.blade.php`
- `resources/views/Owner/data-toko/index.blade.php`
- `database/seeders/OwnerSeeder.php`
- `database/seeders/SuperAdminSeeder.php` (sudah berisi password `password`)

## Verifikasi

- Feature test dashboard tanpa data:
  - SuperAdmin: omzet/kepuasan/SLA tidak menampilkan 100% dan memuat
    "Belum Ada Data" / "Belum Ada Pembanding".
  - Gudang: tanpa gudang, akurasi/SLA tidak 100%.
  - Admin: tugas 0 tidak menampilkan progress 4%.
- Feature test sidebar:
  - Semua tombol grup collapsible memiliki `aria-expanded="true"`.
  - Grup SuperAdmin memiliki `sa-group-open`.
- Feature test logo toko:
  - Toko tanpa logo menampilkan ikon storefront, bukan foto produk.
- Targeted test, full test, lalu QA browser semua role.
- Commit satu file per commit dengan `--no-verify`; tidak push.
- File `.env` tidak boleh ikut commit.

## Di luar scope

- Optimasi performa (Tailwind/Chart.js/font/Material Symbols) ditunda sampai
  hosting production stabil.
- Deploy production cPanel dan seeding production tidak termasuk batch ini.
