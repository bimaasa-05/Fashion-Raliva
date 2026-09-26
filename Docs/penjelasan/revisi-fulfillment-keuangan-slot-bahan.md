# Penjelasan: Fulfillment, Stok, Keuangan, Slot, Bahan

Untuk: Admin, Owner, Gudang, Produksi (nonteknis).

## 1. Cara terima barang pisah dari status customer (perbaikan pengertian)

Dulu: offline = ambil di toko, online = diantar. Itu salah pengertian — sekarang:

- **Status customer**: Online (user terdaftar) vs Offline (tamu/walk-in).
- **Cara terima barang**: Diantar kurir vs Ambil di toko — bebas dikombinasi. Customer offline boleh minta diantar; customer online boleh ambil sendiri.

Di form Tambah Pesanan ada dua pilihan terpisah: Status Customer + Cara Terima Barang.
Di halaman Pengiriman ada dua tombol per pesanan: **Selesai (Diambil)** untuk ambil di toko,
**Input Resi + Kirim** untuk diantar. Tombol Selesai hanya muncul untuk ambil;
pesanan diantar wajib lewat resi. Tracking customer ikut cara terima (Siap Diambil
vs Dikirim + resi), bukan status customer.

## 2. Ambang menipis otomatis 10

Admin tidak lagi mengisi ambang menipis per varian (tambah maupun edit).
Sistem menulis 10 otomatis; status Kritis (≤5) / Menipis (≤10) / Aman tetap jalan.
Data lama tidak diubah.

## 3. Pemasukan Investor/Modal masuk omzet, bukan saldo tarik

Setuju dengan usulan: uang Investor/Modal yang masuk saldo bisa ditarik sebagai
profit — itu lubang. Sekarang:

- **Investor / Modal** → tercatat sebagai omzet saja (masuk laporan Total Omzet,
  **tidak** menambah saldo yang bisa dicairkan).
- **Penjualan / Komisi / Lainnya** → tetap masuk saldo seperti semula.
- Bonus perbaikan: pengeluaran Admin yang sebelumnya tidak mengurangi saldo kini
  ikut mengurangi + tercatat di jurnal (sama seperti Owner).
- Form Pemasukan Owner tampilannya disamakan dengan Pengeluaran (grid + tabel
  + filter kategori), plus catatan bahwa Investor/Modal tidak masuk saldo tarik.

## 4. Slot habis → dua pilihan

Produk tidak bisa ditambah saat kuota habis. Kini bukan sekadar error:
halaman Beli Slot menampilkan peringatan + dua pilihan: **Ajukan Slot Satuan**
(flow lama, menunggu SuperAdmin) atau **Beli Paket** (langsung aktif).
Pembelian paket kini bisa dilakukan **Admin juga** (sebelumnya Owner saja).

## 5. Tombol +Bahan Produksi

Tombol hanya ada untuk pesanan yang sudah di-Accept dan berstatus Diproses
(di status lain memang teks biasa — itu desain, bukan bug). Di dalam modalnya
kini ada kotak **"Bahan dibutuhkan (dari Gudang)"** berisi bahan yang diinput
Gudang (mis. Kancing × 8 pcs), sehingga Produksi tahu kebutuhan vs tambahannya.
Form tambah per pesanan tetap seperti semula.

## Verifikasi

- Suite penuh: **134 passed + 2 skipped + 1 risky** (skip = DB lokal 1 toko;
  risky = test komplain lama tanpa assertion; keduanya bukan regresi).
- Test baru: kombinasi fulfillment 2×2 + alih (8), omzet vs saldo (4),
  slot habis + beli paket (3), kebutuhan bahan di modal (assert).
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Sisa: verifikasi browser manual per peran.
