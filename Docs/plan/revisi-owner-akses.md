# Revisi — Owner di Rekap (Bug) + Co-Access

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Bug: blok baris Owner setelah `return` (mati) → pindahkan + perketat test.
- Co-access: `OwnerContext::ownedStoreIds()` fallback ke assignment aktif;
  `SaldoController` pakai `OwnerContext::currentStore()`; pengajuan toko tetap
  kepemilikan murni.
- Data: assignment aktif coworker → toko 3.

## Paket 1 — Bug baris Owner

- [x] Blok owner sebelum `return`; test assert nama owner tampil + ROI.

## Paket 2 — Co-access

- [x] Fallback OwnerContext; SaldoController konsisten.
- [x] Data assignment coworker → toko 3 (reversibel).
- [x] Test: owner-coverage (dengan assignment bisa; tanpa tetap terkunci).

## Paket 3 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-owner-akses.md`, manual 2 akun.
