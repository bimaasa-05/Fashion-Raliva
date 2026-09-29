# Revisi — Gudang, Sosmed, Sesi, Laporan

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Gudang: Owner kelola penuh (CRUD + tugaskan staff); cegah nonaktifkan
  gudang terakhir aktif / berstok.
- Sosmed: master platform oleh SuperAdmin (seed TikTok/Instagram/YouTube);
  Owner pilih dari daftar atau custom (nama + link); tampil di about toko.
- Sesi: multi-role dipertahankan; perbaiki resolusi cookie area.
- Laporan Admin: tren setengah + donut rupiah + tabel ringkas selesai.

## Paket 1 — Gudang Owner

- [x] `Owner\GudangController`: store/update/toggle/assign + guard.
- [x] View: Tambah + aksi + kelola staff.
- [x] Test terkait (`OwnerGudangTest` 4/4).

## Paket 2 — Sosmed

- [x] Migrasi `store_socials` + model; CRUD master SuperAdmin.
- [x] Owner pilih/custom; tampil di about.
- [x] Test terkait (`SosmedTest` 4/4).

## Paket 3 — Sesi

- [x] Halaman 419 ramah + guard fetch + notif `?expired=1` (pivot: gejala = sesi idle kedaluwarsa, bukan cookie).
- [x] Test terkait (`SessionExpiryTest` 3/3).

## Paket 4 — Laporan

- [x] Sinkron `Order::STATUS_PENDAPATAN` + notif tanpa-toko + label (pivot: data sudah ada, yang rusak = set status & empty-state).
- [x] Test render + angka (`AdminLaporanSyncTest` 2/2).

## Paket 5 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan
      `Docs/penjelasan/revisi-gudang-sosmed-sesi-laporan.md`, manual.
