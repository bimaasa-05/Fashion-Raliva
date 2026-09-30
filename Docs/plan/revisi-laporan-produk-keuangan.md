# Plan: Laporan Admin, Data Produk, Laporan Owner & Keuangan

Tanggal: 30 September 2026. Status: PERENCANAAN — belum dieksekusi.

## A. Laporan Operasional Admin (view)

1. **Grafik omzet tanpa label angka statis** — nilai `..JT` di atas tiap bar disembunyikan
   via CSS, hanya muncul saat hover (tooltip native `title` sudah ada di `ralivaBars`).
   Sentuh `resources/views/partials/ui-scripts.blade.php` (fungsi `ralivaBars`);
   berlaku konsisten untuk semua pemakai `data-bars`.
2. **Samakan div dua kartu grafik** — unifikasi class kartu Omzet dan Metode Terbanyak
   (`p-6`, heading `premium-heading`, `min-h` setara) agar sejajar.
3. **Export Excel + PDF ala Owner** — baru:
   - `app/Exports/AdminLaporanExport.php` (maatwebsite/excel; sheet Ringkasan +
     Pesanan Selesai), scope toko Admin + filter `dari/sampai`.
   - View PDF khusus `Admin/laporan/pdf.blade.php` + method `exportPdf` (dompdf).
   - Route `admin/laporan/export-excel`, `admin/laporan/export-pdf`;
     tombol di samping filter tanggal.
   - Test: unduh xlsx + pdf 200 + isi hanya toko scope.

## B. Data Produk Admin

1. **Slot over** — validasi + halaman dua opsi (`admin.slot`) SUDAH ADA; yang dikerjakan:
   - Seeder `ProductSlotPackageSeeder` (3 paket aktif: Hemat 10 slot Rp50rb,
     Bisnis 25 Rp100rb, Sultan 50 Rp175rb) + registrasi di `DatabaseSeeder`.
   - Pastikan daftar paket tampil di halaman `admin.slot`.
   - Test: tambah produk saat 5/5 ditolak + redirect `admin.slot`.
2. **Minimalisir animasi tambah produk** (`Admin/produk/index.blade.php`):
   - Matikan auto-rotate foto galeri saat hover (interval 1,2 dtk).
   - Hapus class `animate-spin` di slot foto; pertahankan transisi ringan.
3. **Switch kartu/tabel + filter** — tiru pola SuperAdmin manajemen-toko:
   - Toggle Kartu/Tabel via `localStorage` (kedua mode render server).
   - Filter server-side `?kategori=&status=` dikombinasi search `q` existing.
   - Test: render kedua mode + filter kategori/status.

## C. Laporan Owner — Produk Terlaris

- Ganti Chart.js bar horizontal dengan renderer bawaan `data-leaderboard`
  (rank 1 emas/2 perak/3 perunggu + progress + "X pcs").
- Controller: mapping `$top` (`nama/terjual`) → `name/meta/display/pct`.
- Test: assert markup `data-leaderboard` + pct benar.

## D. Form Pemasukan/Pengeluaran (Rp + validasi)

1. Partial global baru `partials/rupiah-input.blade.php` (dari versi Admin/produk:
   dukung desimal + hidden-clone); dimuat di layout Admin DAN Owner.
2. Form Admin (`transaksi/index`): input `type=number` → pola Owner
   (prefix Rp + `data-rupiah` + strip saat submit).
3. Selaraskan validasi Admin ke Owner: nominal `min:1`, kategori pengeluaran wajib,
   tanggal `before_or_equal:today`, TAMBAH field tanggal di pemasukan.
4. Test: submit `"1.000.000"` lolos; assert `data-rupiah` di kedua form Admin.
5. Catatan: `Owner/saldo/index.blade.php` legacy yatim (tak dirujuk) — tidak disentuh.

## E. Card Perkiraan Keuntungan Owner

- Grid `L84` `Owner/keuangan/index.blade.php`: `grid-cols-2 md:grid-cols-4` →
  `grid-cols-2 md:grid-cols-3 xl:grid-cols-5` agar 5 kartu (Omzet, Laba Kotor,
  EBITDA, Laba Bersih, ROI) sejajar 1 baris di layar lebar + samakan tinggi kartu.
- Nol perubahan controller/rumus. Test: assert 5 kartu tampil 1 section.

## Verifikasi (saat eksekusi)

`php -l` file diubah → `view:cache` → `view:clear` → `git diff --check` →
test baru + regresi (laporan, produk, transaksi, keuangan) → update
`Docs/penjelasan/revisi-gudang-sosmed-sesi-laporan.md` (atau doc baru).

Estimasi: ±14 file ubah, ±6 file baru, ±6 test baru. Urutan eksekusi: A → B → C → D → E.
