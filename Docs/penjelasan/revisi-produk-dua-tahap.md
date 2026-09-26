# Penjelasan: Tambah Produk 2 Tahap

Untuk: Admin, Owner (nonteknis).

## Ringkasan

Form Tambah Produk yang dulu satu layar raksasa (foto + info + rencana + varian sekaligus, gagal satu bagian = seluruh produk ditolak) kini **dibagi dua tahap**:

| Tahap | Isi | Hasil |
|---|---|---|
| **1 — Tambah Produk** | Foto + info dasar + variasi & stok | Produk tersimpan (status Menunggu moderasi), boleh belum ada rencana |
| **2 — Rencana Produksi** | Target + bahan + biaya operasional | Modal/batch/margin terhitung, dari halaman detail produk |

## Cara pakai yang baru

1. Klik **Tambah** → isi foto, nama, kategori, tipe, harga, deskripsi, ukuran, warna, stok → **Simpan Produk**.
2. Buka **Detail** produk tersebut → bila belum ada rencana, ada tombol **"Buat Rencana Produksi"** → isi target, tambah bahan (bisa dari katalog atau manual), tambah biaya operasional → pantau ringkasan modal/margin live → **Simpan Rencana**.
3. Rencana hanya bisa diisi **sekali**. Setelah ada, perubahan lewat tombol **Edit** seperti biasa (tetap melalui persetujuan Super Admin).

## Yang berubah di balik layar (ringkas, teknis)

- `store()` kini hanya master (1 transaksi: produk + foto + varian + stok); target/resep/operasional pindah ke `storeRencana()` (1 transaksi sendiri).
- Route baru: `POST admin/produk/{product}/rencana`.
- Validasi warna satu lapis dengan validasi utama (sebelumnya dua lapis terpisah).
- Test disesuaikan ke 2 request + 3 test baru (tanpa rencana sah, tolak rencana ganda, tolak luar scope).

## Verifikasi

- 15/15 test produk hijau; regresi penuh 65 passed + 1 skip (skip karena DB lokal hanya punya 1 toko — butuh toko kedua untuk uji scope gudang; bukan bug).
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Catatan test: ganti user antar-request wajib `flushSession` dulu (pola `actingAsFresh`), kalau tidak auth hilang.

## Belum dikerjakan

- Verifikasi browser manual (tambah tahap 1, lengkapi rencana, mobile).
