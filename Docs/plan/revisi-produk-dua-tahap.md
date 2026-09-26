# Revisi — Tambah Produk 2 Tahap

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati (pisah 2 tahap), dieksekusi.

Scope: Admin produk (create) + test terkait. Edit/approval workflow tanpa perubahan.

## Keputusan yang dikunci

- **Tahap 1 — Tambah Produk**: foto + info dasar + variasi & stok; produk tersimpan status `pending`, rencana boleh kosong ("Belum ada rencana produksi").
- **Tahap 2 — Rencana Produksi**: target + resep + operasional, via form di detail produk; hanya bila produk **belum punya resep** (punya resep → gunakan Edit/pengajuan perubahan, agar workflow approval tidak terbypass).
- Validasi warna (`WarnaPalet`) dilebur ke rules utama (satu lapis).
- Tiap tahap 1 transaksi penuh (master: produk + foto + varian + stok; rencana: target + resep + operasional).

## Paket 1 — Controller + route

- [x] `store()`: hapus target/resep/operasional/cost-calc; validasi master saja; 1 transaksi (produk + foto + varian + stok + gudang).
- [x] `storeRencana(Product)`: scope toko + tolak bila sudah ada resep; normalisasi + validasi rencana; map + cost calc; 1 transaksi (update target/modal/biaya + createMany resep/operasional).
- [x] Route `POST admin/produk/{product}/rencana` (`admin.produk.rencana.store`).
- [x] Rules `warna`/`warna_hex` (`nullable|array`) + panggil `normalizeOptionalSubmissionOrFail` sebelum/sebagai bagian validasi.

## Paket 2 — UI

- [x] Modal tambah: hapus seksi Rencana Produksi (HTML + submit-guard rencana).
- [x] Modal baru `#modal-rencana-produk` (`#form-rencana`, action diisi JS per produk): target + operasional dinamis + resep dinamis + ringkasan live (IIFE rencana di-retarget ke ID `rp-*`).
- [x] Detail produk: tombol "Buat Rencana" (tampil bila resep kosong) + JS isi action form + harga acuan.

## Paket 3 — Test + verifikasi

- [x] Sesuaikan `ProductRecipeTest` (2 request), `ProductFormPolishTest` (urutan modal tanpa Rencana; format angka), `ProductColorValidationTest` (payload tahap 1 + rencana bila perlu).
- [x] Test baru: produk tanpa rencana sah + tampil "Belum ada rencana"; rencana susulan tersimpan + modal terhitung; tolak rencana ganda; tolak luar scope.
- [x] `php -l`, `view:cache` → `view:clear`, `git diff --check`, regresi produk.
- [x] Penjelasan: `Docs/penjelasan/revisi-produk-dua-tahap.md`.
- [ ] Manual: tambah tahap 1, lengkapi rencana, mobile.
