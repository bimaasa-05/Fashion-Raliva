# Revisi — Tab QC + Rincian Gudang

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi bertahap.

Scope: Pemeriksaan Kualitas (Produksi) + Data Stok & menu baru Kekurangan (Gudang).

## Keputusan yang dikunci

- Tab QC server-side `?tab=menunggu|siap` (pola `withQueryString()`), **default Menunggu**.
- Tab **Menunggu**: QC + Packing, Gagal—Hubungi Admin (sudah ada). Tab **Siap Untuk Dikirim**: read-only + ringkasan lulus/gagal/kekurangan.
- Rincian bahan (resep Admin) tampil **di modal detail stok** Gudang, bukan halaman baru.
- Detail stok = varian + **riwayat keluar-masuk produk** (10 terakhir).
- Daftar kekurangan produksi di Gudang + tombol **Tandai Sudah Disiapkan** (scope toko).

## Paket 1 — Tab Pemeriksaan Kualitas

- [x] `index()`: terima `tab` (menunggu|siap), default menunggu; query sesuai tab + stats tetap dua-duanya.
- [x] View: dua tab aktif/nonaktif, konten per tab (siap = read-only, badge lulus/gagal/kekurangan).
- [x] Test: default menunggu; `?tab=siap` menampilkan siap_kirim; tombol QC tetap hanya di tab menunggu.

## Paket 2 — Modal stok: resep + riwayat

- [x] `StokController@index`: eager-load `materialRequirements` (+ `material`) + ambil 10 movement terakhir per produk (`StockMovement` by variant produk tsb).
- [x] Modal `stok-detail-*`: seksi **Rincian Bahan** (nama, jumlah/unit, satuan, biaya) + pesan bila tanpa resep; seksi **Riwayat Stok** (tanggal, tipe, jumlah, alasan, pencatat).
- [x] Test: resep Admin tampil di modal; riwayat tampil; produk tanpa resep menampilkan pesan kosong.

## Paket 3 — Daftar Kekurangan di Gudang

- [x] Menu **Kekurangan** (grup Persediaan) → `GET gudang.kekurangan`.
- [x] Query: `kekurangan_gudang > 0` + scope toko aktif user (bukan scope gudang fisik) → nomor, produk × qty, kurang N, tanggal QC.
- [x] `POST gudang.kekurangan/{order}/siapkan`: set `kekurangan_gudang = 0`, log, notifikasi Produksi se-toko (+ Admin se-toko).
- [x] Test: daftar, tandai selesai + notifikasi, scope toko, order tanpa kekurangan tidak tampil.

## Paket 4 — Verifikasi

- [x] `php -l`, `view:cache` → `view:clear`, `git diff --check`.
- [x] Test baru + regresi Gudang & Produksi.
- [ ] Manual: tab QC, modal stok (bahan+riwayat), kekurangan + tombol, mobile.
- [x] Penjelasan: `Docs/penjelasan/revisi-qc-tab-rincian-gudang.md`.
