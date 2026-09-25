# Penjelasan: Tab QC + Rincian Gudang

Untuk: tim Produksi, tim Gudang, Admin, Owner (nonteknis).

## Ringkasan

Tiga peningkatan:

1. **Halaman QC punya 2 tab**: Menunggu (default) dan Siap Untuk Dikirim.
2. **Modal Data Stok Gudang** sekarang menampilkan **Rincian Bahan** (resep dari Admin) + **Riwayat Stok** produk.
3. **Menu baru "Kekurangan"** di Gudang: daftar pesanan yang kurang dari hasil produksi + tombol Tandai Sudah Disiapkan.

## 1. Tab Pemeriksaan Kualitas

| Tab | Isi |
|---|---|
| **Menunggu** (buka default) | Seperti semula: tombol QC + Packing dan Gagal — Hubungi Admin |
| **Siap Untuk Dikirim** | Order yang sudah lulus QC, hanya-baca (tombol timeline saja) + ringkasan lulus/gagal dan "+N dari Gudang" bila ada kekurangan |

Angka di tiap tab menunjukkan jumlah order (sama dengan kartu statistik di atasnya).

## 2. Modal stok: resep + riwayat

Buka Data Stok → klik detail produk. Selain info lama (total stok, per varian, nilai persediaan), kini ada:

- **Rincian Bahan**: daftar bahan per unit produk — nama, jumlah per unit, satuan, biaya per unit — persis seperti yang diinput Admin saat menambah produk. Bila produk belum punya resep, tertulis jelas "Belum ada resep bahan".
- **Riwayat Stok (10 Terakhir)**: 10 pergerakan terakhir produk itu di gudang tersebut — waktu, jenis (masuk/keluar/mutasi/penyesuaian), jumlah (+/-), alasan, dan petugas. Tombol "Lihat Riwayat" tetap ada untuk riwayat lengkap semua produk.

## 3. Menu Kekurangan di Gudang

Jawaban atas "apakah di gudang sudah semua": **sekarang sudah**. Sebelumnya notifikasi kekurangan produksi hanya masuk inbox dan link-nya ke dashboard — tidak ada tempat kerja.

- Menu **Kekurangan** (grup Persediaan, ikon assignment): kartu total kekurangan (pcs) + daftar order dengan `kekurangan > 0` (nomor, toko, produk × qty, badge kekurangan, tanggal QC) — tabel di desktop, kartu di HP.
- Tombol **"Tandai Sudah Disiapkan"** (dengan konfirmasi): kekurangan order jadi 0 (hilang dari daftar) + tim **Produksi se-toko** dan **Admin se-toko** otomatis dinotifikasi bahwa kekurangan sudah disiapkan dan bisa dipacking ulang.
- Notifikasi "Kekurangan Produksi" dari QC sekarang membuka halaman ini langsung.
- Scope toko dijaga: order toko lain tidak tampil dan tidak bisa ditandai.

## Sisi teknis (Admin IT)

- QC: `PemeriksaanKualitasController@index(Request)` menerima `?tab=menunggu|siap` (default menunggu, pola `withQueryString()`); view tab aktif + konten per tab; modal QC hanya dirender di tab menunggu.
- Stok: `StokController@index` eager-load `materialRequirements` + 10 movement terakhir per produk (grouping PHP biasa — `groupBy()->only()` pada Eloquent Collection memicu error `getKey`, sudah dihindari); view modal tambah 2 seksi.
- Kekurangan: `Gudang\KekuranganController` (`index` + `siapkan`), route `gudang.kekurangan` / `gudang.kekurangan.siapkan`, menu sidebar, log `gudang.kekurangan.siapkan`.
- Test: `tests/Feature/QcTabRincianGudangTest.php` — 5 test (default tab, tab siap read-only, modal resep + riwayat, daftar + tombol + notifikasi, scope toko).
- Verifikasi: `php -l`, `view:cache/clear`, 5/5 test + regresi 43/43 lulus, `git diff --check` bersih.
- Catatan test: (1) ganti user lintas area wajib `flushSession` dulu; (2) test Gudang wajib mengunci session `gudang_active_warehouse_id` ke warehouse yang sama dengan data uji — kalau tidak, request memakai gudang pertama (`orderBy nama_gudang`) dan data uji tak terlihat; (3) label badge satu baris agar `assertSee` cocok; (4) `OrderPriorityCancelTest` diperketat memakai atribut `data-nomor` baris tabel — posisi mentah di HTML tercemar dropdown notifikasi header (flaky, tie `created_at` presisi detik).

## Belum dikerjakan

- Verifikasi browser manual (tab QC, modal stok bahan + riwayat, menu kekurangan + tombol, mobile).
