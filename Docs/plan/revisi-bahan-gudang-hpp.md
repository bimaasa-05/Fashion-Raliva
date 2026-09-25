# Revisi — Bahan Pindah ke Gudang + HPP Manual

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

Scope: Admin produk (create/edit/approval), Gudang (input bahan), Produksi (tampil bahan).

## Keputusan yang dikunci

- Form tambah 1 langkah simple: foto + info + **HPP/Modal** (baru, sebelum Harga Jual) + variasi & stok. Tanpa target/resep/operasional.
- HPP manual tersimpan di `modal_produksi`; margin (= harga − HPP) otomatis di ringkasan; tanpa batch.
- Target produksi + biaya operasional **dihapus** dari alur Admin.
- Gudang pemilik bahan: notifikasi saat Admin submit (pending) → isi bahan (nama + jumlah + satuan, reuse `product_material_requirements`, biaya 0).
- Resep tersalin otomatis → bahan order (`sumber=admin`, jumlah = per-unit × qty) saat Produksi accept (belum ada mekanisme ini — dibangun).
- Edit + approval: seksi target/resep/operasional dihapus penuh.
- Produksi nambah bahan: fitur per-order existing (verifikasi saja).

## Paket 1 — Admin create (HPP, tanpa rencana)

- [x] `store()`: tambah `hpp` (numeric, tampil sebelum harga); hapus `storeRencana()` + route + helper tak terpakai.
- [x] Modal tambah: field HPP/Modal; hapus modal `#modal-rencana-produk` + IIFE `rp-*` + `openRencanaModal`.
- [x] Detail: hapus tombol "Buat Rencana"; ringkasan HPP + margin (atau "Belum ada bahan").

## Paket 2 — Edit + approval tanpa rencana

- [x] Modal Edit: hapus seksi Rencana + JS + atribut `data-resep-*` tombol Edit (detail tetap).
- [x] Approval: hapus apply/compare resep-operasional-target; tolak request perubahan resep otomatis.
- [x] Tulis ulang test terkait.

## Paket 3 — Gudang bahan + alir ke Produksi

- [x] Menu Bahan Produk: daftar produk toko tanpa bahan + form dinamis (nama + jumlah + satuan) → `product_material_requirements` (scope toko, 1 transaksi).
- [x] `store()` produk → notifikasi Gudang se-toko (deep-link menu Bahan).
- [x] `accept()`: salin resep tiap produk item → `production_order_bahan` (`sumber=admin`, jumlah × qty, idempoten); tampil di Produksi (sumber=admin) + Stok (HPP asli).

## Paket 4 — Verifikasi

- [x] `GudangBahanTest` baru; `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan `Docs/penjelasan/revisi-bahan-gudang-hpp.md`.
- [ ] Manual: tambah produk (HPP), input bahan Gudang, accept produksi + tambah bahan, mobile.
