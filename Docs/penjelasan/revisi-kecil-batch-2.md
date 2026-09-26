# Penjelasan: Revisi Kecil Batch 2

Untuk: Admin, Gudang, Produksi, SuperAdmin (nonteknis).

## 1. Stok minimal 10 saat tambah produk

Kolom "Ambang Menipis" dihapus dari form tambah maupun edit. Sistem menulis
10 otomatis untuk setiap varian baru. Aturan: stok per varian minimal 10
(divalidasi server + browser). Data lama tidak diubah; status Kritis (≤5) /
Menipis (≤10) tetap jalan.

## 2. Bahan Produk Gudang urut terbaru

Daftar diurutkan berdasarkan produk terbaru (`created_at` desc), tidak lagi
bahan-kosong-dulu. Badge "Belum ada" tetap tampil sebagai info.

## 3. Tambah Pesanan tanpa pilihan Cara Terima

Seksi radio "Cara Terima Barang" (Diantar/Ambil) dihapus dari modal Tambah.
Cara terima terisi otomatis: customer Online → Diantar, Offline (tamu) → Ambil.
Keputusan kirim vs ambil tetap bisa diubah per order di Pengiriman / tombol Alihkan.

## 4. Jumlah terisi otomatis = total pesanan

Modal Selesai Produksi (`Jumlah Berhasil`) dan modal QC (`Jumlah Lulus`)
kini terisi awal sebanyak total pcs pesanan — tinggal tekan simpan bila semua
sesuai, ubah manual bila ada yang gagal.

## 5. Moderasi: 2 tab + picker kalender terlihat

Detail produk di Moderasi SuperAdmin kini punya tab **Informasi Produk**
(foto, tipe, kategori, varian, slot, deskripsi) dan **Informasi Bahan**
(tabel bahan dari Gudang; "Belum ada bahan" bila kosong).
Ikon kalender/jam pada input tanggal-jam (modal Proses) dipaksa selalu terlihat
via `color-scheme` — sebelumnya hilang di tema gelap.

## Verifikasi

- Suite penuh: **140 passed + 2 skipped + 1 risky** (skip = DB 1 toko;
  risky = test komplain lama; bukan regresi).
- Test baru: tolak stok <10, urut terbaru, default fulfillment, prefill qty,
  tab moderasi.
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Sisa: verifikasi browser manual.
