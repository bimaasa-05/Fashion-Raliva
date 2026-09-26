# Revisi — JS Produksi Mati + Backfill Bahan

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Akar 3 gejala (+Bahan/Selesai tak bisa diklik, countdown "Memuat..." terus):
  `@include('partials.countdown-produksi')` bersarang di dalam blok `<script>`
  halaman Data Produksi → seluruh JS halaman gagal parse.
- Order lama yang terlanjur accept sebelum fitur salin: **backfill manual**
  (tombol sekali jalan, idempoten).

## Paket 1 — Perbaiki script

- [x] Pindahkan include countdown ke luar `</script>` (pola Owner).
- [x] Pindai halaman Produksi lain (QC, Selesai, Riwayat, bahan) pola serupa.
- [x] Test penjaga struktur blade + render modal.

## Paket 2 — Backfill

- [x] Tombol backfill (visible bila ada order tanpa bahan-admin): salin resep,
      idempoten, scope toko, log.
- [x] Test: backfill muncul + idempoten.

## Paket 3 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-produksi-js-bahan.md`, **manual browser wajib**.
