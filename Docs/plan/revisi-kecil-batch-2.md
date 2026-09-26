# Revisi Kecil Batch 2

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Stok minimal 10 hanya saat tambah (Edit boleh 0).
- Bahan Produk Gudang urut `created_at` desc (cabut ordering bahan-kosong).
- Hapus seksi Cara Terima Barang (dua radio) dari Tambah Pesanan; fulfillment
  default otomatis (online→diantar, offline→ambil); Status Customer tetap.
- Selesai/QC prefill total qty; moderasi 2 tab; picker CSS.

## Paket 1 — Stok + urutan bahan

- [x] `store()` produk: `varian_stok.*.stok` min 10 + pesan.
- [x] `BahanProdukController@index`: `orderByDesc('created_at')` saja.
- [x] Test keduanya.

## Paket 2 — Tambah pesanan tanpa Cara Terima

- [x] View: hapus seksi Cara Terima; controller default otomatis.
- [x] Test default + alih.

## Paket 3 — Prefill, tab, picker

- [x] Selesai/QC default total qty; moderasi tab Bahan; CSS `color-scheme`.
- [x] Test render.

## Paket 4 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-kecil-batch-2.md`, manual.
