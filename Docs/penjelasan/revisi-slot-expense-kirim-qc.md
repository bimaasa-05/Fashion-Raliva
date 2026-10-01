# Penjelasan Revisi: Slot Jadi Pengeluaran, Kirim Offline, Split QC

Tanggal: 2026-10-01. Status: **belum di-commit** (semua perubahan masih di
working tree untuk direview).

Tiga request yang dikerjakan:

1. **Admin** — pembelian slot (paket maupun fleksibel) tercatat sebagai
   **pengeluaran toko** kategori `Slot`.
2. **Admin** — tombol pengiriman pesanan offline yang mati + pilihan
   Antar/Ambil saat buat pesanan.
3. **Produksi** — tabel Hasil QC (berhasil/gagal) terpisah dari Hasil
   Produksi + kolom Catatan; angka tampil saat Gagal/Selesai (tak lagi `-`).

---

## 1. Pembelian slot = pengeluaran (tanpa potong wallet)

Keputusan: expense dicatat untuk **Admin + Owner** (beban kas toko yang
sama), **tanpa** mengurangi saldo wallet — uang dibayar via transfer bank
eksternal + bukti, bukan dari saldo.

- Fleksibel: expense dibuat saat **approve SuperAdmin**
  (`SuperAdmin/SlotProdukController@approvePurchase`, dalam transaksi yang
  sama setelah `grant()`). Bukan saat pengajuan pending — kalau ditolak,
  tidak jadi beban. Idempoten via `firstOrCreate(store_id + nama req #id)`
  (guard grant-ganda sudah ada sebelumnya).
- Paket: expense dibuat saat **beli** (`Admin/SlotController@beliPaket`,
  `Owner/PaketSlotController@purchase`) — paket langsung aktif tanpa
  approval. Nominal = harga akhir setelah promo (Admin ikut dihitung
  `$hargaAkhir` seperti Owner; sebelumnya Admin menghitung diskon tapi
  tidak memakainya). Grant manual/gratis TIDAK menciptakan expense.
- Format: `nama = 'Pembelian N slot fleksibel (req #id)'` /
  `'Paket slot "X" (N slot)'`, `kategori = 'Slot'`, `nominal = total_harga`,
  `tanggal = hari ini`, `dibuat_oleh = user yang menyetujui/membeli`.
- `'Slot'` ditambahkan ke daftar kategori pengeluaran
  (`Admin/TransaksiController`, `Owner/SaldoController`) agar filter
  konsisten; daftar `distinct` otomatis ikut setelah ada datanya.
- Efek ke laporan: expense slot otomatis masuk `total_pengeluaran` /
  `total_bersih` di Transaksi, Saldo Owner, dan Laporan (semua baca
  `StoreExpense::sum(nominal)`).

---

## 2. Pengiriman pesanan offline

Dua akar masalah diperbaiki:

1. **Tombol mati total** — `Admin/pengiriman/index.blade.php:296` mengecek
   `@if ($item['tipe'] === 'offline')` padahal nilai tipe hanya
   `ambil`/`diantar`, sehingga modal `modal-selesai-ambil-{id}` tidak pernah
   dirender. Satu baris → `=== 'ambil'`. Tombol **Selesai (Diambil)** kini
   membuka modal konfirmasi seperti semula.
2. **Offline selalu ambil** — `DataPesananController@store` memaksa
   `metode_fulfillment = ambil`. Kini form buat pesanan (offline saja) punya
   radio **Ambil di Toko / Diantar Kurir** + (bila Diantar) select kurir
   (hanya yang aktif untuk toko) + layanan + preview ongkir. Server:
   validasi kurir/layanan milik toko, **tarif diambil dari server**
   (`shipping_services.tarif`, input ongkir mentah diabaikan), masuk
   `checkout.total_ongkir` + `orders.total_ongkir` → `grand_total`
   (via `PricingService::computeTotals`). Kurir & resi tetap dilengkapi
   belakangan di Pengiriman (alur `simpanResi` tidak berubah).
3. Hint **"Belum ada layanan kurir — tambah dulu di menu Metode
   Pengiriman"** bila `shipping_services` kosong (penyebab select layanan
   terlihat mati/kosong; kurir global read-only, layanan milik toko).

---

## 3. Split Hasil Produksi vs Hasil QC + Catatan

Skema (migrasi `2026_10_01_191859`, aditif, sudah dijalankan di DB lokal):

- `orders.hasil_produksi_berhasil/gagal` — snapshot angka produksi persis
  sebelum QC menimpanya.
- `orders.hasil_qc_lulus/gagal` — angka QC terakhir (termasuk jalur Gagal).

Semantik yang dijaga: `orders.jumlah_berhasil/gagal`, `kekurangan_gudang`,
dan alur shortage Gudang **tidak berubah sama sekali** (Gudang men-top-up
`jumlah_berhasil`; test shortfall lama hijau tanpa modifikasi).

Perilaku baru:

- `PemeriksaanKualitasController@store`: snapshot produksi → tulis
  `hasil_qc_*` → tulis `jumlah_*` seperti dulu → redirect ke
  **`?tab=siap`** (sebelumnya `back()` bertahan di tab menunggu padahal
  pesanan sudah pindah).
- `tandaiGagal()`: tulis `hasil_qc_lulus = 0`, `hasil_qc_gagal = total`
  (+ snapshot produksi); status tetap `menunggu_qc` + flag Admin; catatan
  tetap wajib min. 10 karakter. Redirect `back()` — pesanan tetap di tab
  menunggu dengan badge **Menunggu Admin** + angka + catatan tampil.
- Tampilan (prioritas `hasil_qc_*` → baris `quality_checks` → `-`;
  produksi `hasil_produksi_*` → `jumlah_*`):
  - Tab QC produksi: kolom baru **Hasil QC** + **Catatan QC**; modal QC
    menampilkan box **Hasil QC Terakhir** (lulus/gagal + catatan +
    tanggapan Admin) bila pernah ada upaya QC/gagal.
  - Produk Selesai: kolom Lulus/Gagal QC baca `hasil_qc_*` + kolom
    **Catatan QC** baru.
  - Riwayat Produksi: baris Produksi vs QC terpisah + catatan.
  - `modal-produksi-detail`: seksi **Hasil QC** dan **Hasil Produksi**
    tampil berdampingan (dulu XOR), catatan QC gabungan
    (`qc.catatan ?? tanggapan Admin`; box flag gagal tetap ada).
- Data lama (sudah QC sebelum migrasi): snapshot null → fallback ke
  `jumlah_*`/baris QC seperti tampilan lama (angka tetap tampil, hanya
  label split yang tidak bisa dibedakan).

Sengaja TIDAK membuat baris `quality_checks` baru di jalur gagal: agar
`sum()` di Pelaporan Produksi tidak double-count saat rework lalu QC ulang
(angka terakhir selalu menang via kolom `hasil_qc_*` yang ditimpa).

---

## 4. Bukti uji (semua hijau, belum di-commit)

Baru (13 test):

- `SlotExpenseTest` (5): approve → 1 expense `Slot` sejumlah total +
  wallet tak bergerak; approve 2× → expense tetap 1; beli paket Admin →
  expense harga final; beli paket Owner (dokumen terverifikasi) → expense;
  grant manual → tanpa expense.
- `AdminOfflineFulfillmentTest` (5): offline default ambil + ongkir 0;
  diantar → ongkir tarif masuk grand total (order + checkout); kurir
  nonaktif ditolak tanpa order; modal ambil ter-render; form resi ter-render
  untuk diantar.
- `QcSplitDisplayTest` (3): QC beda dari produksi → snapshot + split
  tercatat, tab siap tampilkan keduanya + catatan; Gagal → 0/total +
  catatan, status tetap, halaman tampilkan angka (bukan `-`); order lawas
  (baris QC tanpa snapshot) tetap tampil via fallback.

Regresi area sentuh — **127 passed, 589 assertions, 0 gagal**:
slot, pesanan, pengiriman, QC/produksi, shortage gudang, customer, produk,
keuangan. Satu regresi sempat merah saat pengerjaan (`$activeStatus`
terlupa saat view `admin.pesanan` diubah ke array eksplisit) — sudah
diperbaiki, seluruh batch hijau.

`php -l` semua file, `view:cache` + `view:clear` bersih,
`git diff --check` bersih.

---

## 5. Batasan jujur

1. Ongkir pesanan offline-diantar = tarif layanan apa adanya (tanpa
   override manual/nego, tanpa `tarif_sekota` per alamat).
2. Kurir yang dipilih saat buat pesanan hanya menentukan ongkir; eksekusi
   (kurir + resi final) tetap di Pengiriman saat `siap_kirim`.
3. Order QC lawas (sebelum migrasi): baris Produksi vs QC bisa menampilkan
   angka yang sama (snapshot tidak ada untuk histori).
4. `Supplier` tetap global (butuh kolom + migrasi bila mau per-toko) —
   di luar cakupan revisi ini.
