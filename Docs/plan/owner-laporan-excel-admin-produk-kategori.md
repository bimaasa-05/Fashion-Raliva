# Plan: Owner Laporan Excel + Admin Form Produk & Kategori

## Latar Belakang
- Owner Laporan Toko saat ini export **CSV** (`Owner/LaporanController::export()` via fputcsv + BOM).
- User minta tombol "csv" diganti **"Excell"** dan output Excel berupa tabel terformat seperti pola beautycare (styled: judul, subtitle, header, border, format Rp, freeze pane).
- Admin form Tambah Produk bagian "Variasi & Stok" masih total dibagi rata & stok **tidak pernah tersimpan** (kolom `stok`/`stok_minimum` tidak ada di tabel `product_variants` dan tidak ada di `ProductVariant::$fillable` → di-drop mass-assignment).
- Admin form Kategori nested di dalam form produk (HTML invalid) & tidak ada deskripsi / search.

## Keputusan User
1. Owner Excel **lengkap** — multiple sheets (Ringkasan + Periode + Top Produk / seperti beautycare `LaporanExport`).
2. Admin Varian Stok — **input stok per varian** (ukuran × warna).
3. Admin Kategori — dropdown **searchable** + create inline via AJAX tanpa reload; setelah save langsung masuk dropdown.

## Struktur

### A. Owner Laporan Excel (packages)
- `composer require maatwebsite/excel` (menarik `phpoffice/phpspreadsheet`).
- `app/Exports/Traits/SheetRaliva.php` — trait gaya tema Raliva (judul/ subtitle/ header offset 3/ lebar kolom / kolom uang / border / freeze). Adaptasi dari beautycare `SheetPengaya.php`, warna `gold-accent` dari CSS Raliva (`#C9A24D` header text, `#F7EFE3` header bg).
- `app/Exports/OwnerLaporanRingkasanSheet.php` — KPI (Pendapatan, Pesanan Selesai, Nilai Refund, Dana Dicairkan).
- `app/Exports/OwnerLaporanPeriodeSheet.php` — tabel 6 kolom (Periode | Pesanan | Pendapatan | Refund | Pencairan | Saldo Akhir) + row Total.
- `app/Exports/OwnerLaporanTopProdukSheet.php` — top 5 produk terlaris.
- `app/Exports/OwnerLaporanExport.php` — `WithMultipleSheets` wrapper.
- `Owner/LaporanController::exportExcel()` — `Excel::download(new OwnerLaporanExport($period, $storeId), ...)`.
- Route baru `owner.laporan.export-excel`.
- View `Owner/laporan/index.blade.php` — link CSV diganti Excel (href ke route baru), keep PDF.

### B. Admin Form Tambah Produk (Varian & Stok per-varian)
- `resources/views/Admin/produk/index.blade.php`:
  - Form kategori **dipindah keluar** dari form produk (jadi modal kecil terpisah) agar HTML valid.
  - Ukuran & warna dipilih → JS render **grid varian** (N = ukuran × warna) dengan input `stok` + `stok_minimum` per baris (name `varian_stok[ukuran|warna][stok]` & `varian_stok[ukuran|warna][stok_minimum]`).
  - Foto slot 4 → 8.
  - Banner read-only: ganti "menunggu persetujuan Owner" → "Super Admin".
- `app/Http/Controllers/Admin/DataProdukController.php`:
  - `ukuran_terpilih` max 50 → 255.
  - Simpan varian (ukuran × warna) dengan stok per varian dari request; jika tidak diisi, default `0`.
  - **Persist stok** ke tabel `warehouse_stocks` (warehouse pertama milik store) — `jumlah_stok`, `stok_minimum`; kalau belum ada warehouse, tetap create varian tanpa stok.

### C. Admin Kategori (searchable + AJAX create)
- `resources/views/Admin/produk/index.blade.php`:
  - Select kategori + input pencarian (filter option saat ketik).
  - Modal kategori kecil (nama + deskripsi) submit via `fetch` ke `admin.kategori.store`, response JSON → append option + auto-select.
- `app/Http/Controllers/Admin/KategoriController.php`:
  - Support `wantsJson()` → respon JSON `{success, kategori}`.
  - Tambah `deskripsi` (sudah divalidasi nullable, tambah input).

## File yang Terdampak
- `composer.json`
- `app/Exports/Traits/SheetRaliva.php` (baru)
- `app/Exports/OwnerLaporanExport.php` + 3 sheet (baru)
- `app/Http/Controllers/Owner/LaporanController.php`
- `routes/web.php`
- `resources/views/Owner/laporan/index.blade.php`
- `resources/views/Admin/produk/index.blade.php`
- `app/Http/Controllers/Admin/DataProdukController.php`
- `app/Http/Controllers/Admin/KategoriController.php`
- `app/Models/ProductVariant.php` (tambah `stok`, `stok_minimum` ke fillable + cast, kalau kolom ditambahkan — TIDAK: disimpan di warehouse_stocks)

## Tambahan D. Cetak PDF Laporan Toko (selesai)
- User minta tombol PDF ala beautycare: klik PDF → **tab baru** berisi file PDF sungguhan (bukan `window.print`).
- Install `barryvdh/laravel-dompdf ^3.1` (sama seperti beautycare; menarik `dompdf/dompdf 3.1.6`).
- `Owner/LaporanController::exportPdf()` + route `owner.laporan.export-pdf` — data via private `reportData($storeId, $period)` (satu sumber kebenaran, dipakai `index` + `exportExcel` via sheet + `exportPdf`).
- View baru `resources/views/Owner/laporan/pdf.blade.php` — dikirim ke dompdf (`Pdf::loadView(...)->stream(...)` → preview PDF di tab bar). Fon `DejaVu Sans` bawaan dompdf, inline CSS, isi sama seperti Excel: Ringkasan (4 KPI), Laporan Periode (tabel + Total), Produk Terlaris (Top 5).
- Tombol PDF di `Owner/laporan/index.blade.php` → `<a target="_blank">` ke `owner.laporan.export-pdf?period=...`.
- Route/view `cetak` (window.print) dari iterasi sebelumnya **dihapus**.

## Verifikasi (status: SEMUA LOLOS)
- `composer require` sukses; `php -l` semua file PHP.
- `php artisan route:list --name=owner.laporan` → `index`, `export`, `export-excel`, `cetak`.
- Render view Admin produk + Owner laporan via server test `127.0.0.1:8000` (login admin/owner).
- Excel export: download via HTTP 200, file ~10 KB dengan 3 sheet (Ringkasan/Periode/Top Produk).
- AJAX kategori: POST JSON `wantsJson` → `{success:true, kategori:{id,nama}}`.
- Submit produk `varian_stok`: 4 varian (M/L × Hitam/Krem) tersimpan + `warehouse_stocks` (stok 12/7/20/5; min 3/2/4/1). Data uji dibersihkan.
- `cetak`: 200 di semua periode (7/30/90/365), berisi Ringkasan/Periode/Produk Terlaris.
- `php artisan view:cache` OK.