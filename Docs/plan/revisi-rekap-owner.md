# Revisi — Owner di Rekap Karyawan (Default)

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Baris Owner = user `stores.owner_id` toko aktif (pemilik tidak tercatat di `store_staff`).
- Metrik Owner: ROI + Pendapatan + Investasi + Bersih (+ customers) dari
  `ringkasanKeuangan` (ROI = bersih/investasi × 100%, rumus yang disepakati).
- Default filter `owner` (index + export); dropdown Owner paling atas.

## Paket 1 — Backend

- [x] `ROLE_FILTERS` + `owner`; default `owner`; `roleKey` OWNER.
- [x] Baris owner + `hitungTotal` cabang owner + `rekapKosong` kunci baru.

## Paket 2 — Tampilan + export

- [x] Dropdown, kolom tabel/mobile/total, footnote; PDF; Excel.

## Paket 3 — Test + verifikasi

- [x] Default owner; kunci ROI; render semua filter.
- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-rekap-owner.md`, manual.
