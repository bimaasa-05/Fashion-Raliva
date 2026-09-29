# Revisi — Bersihkan Akun Legacy Duplikat

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi.

## Keputusan yang dikunci

- Hapus 6 akun legacy (`sa/o/a/g/p/c@gmail.com`) dari DB + cegah kembali.
- o@gmail.com BUKAN login coworker (aman dihapus).
- Dependents: 1 review (c@gmail), 2 notifikasi (a/p@gmail), 1 assignment
  (o@gmail, buatan sendiri) ikut dihapus; sisanya nol.

## Paket 1 — Hapus + seeder

- [ ] Hapus dependents + 6 users (transaksi, FK-safe).
- [ ] UserSeeder: hapus 5 blok gmail; SuperAdminSeeder: hapus blok sa@gmail;
      OwnerSeeder: resolve owner@raliva.test (buat bila belum ada).
- [ ] RalivaDemoSeeder: exclusion list hanya @raliva.test.

## Paket 2 — Verifikasi

- [x] Rekap 1 Owner; suite hijau; penjelasan; manual laporan.
