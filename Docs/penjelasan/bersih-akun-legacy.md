# Penjelasan: Bersih Akun Legacy Duplikat

Untuk: Owner + Admin IT (nonteknis + ringkas teknis).

## Masalah

Di laporan, tipe akun Owner muncul dua kali (`o@gmail.com` tanpa toko dan
`owner@raliva.test` pemilik toko). Penyebab: seeder menghapus tabel
transaksional tapi tidak pernah menghapus users — akun demo generasi pertama
(`sa/o/a/g/p/c@gmail.com`) dikecualikan eksplisit sehingga abadi berdampingan
dengan akun generasi `@raliva.test`.

## Tindakan

- Audit relasi: 6 akun legacy nol relasi kecuali 1 review (c@gmail),
  2 notifikasi (a/p@gmail), 1 assignment (o@gmail, buatan sendiri) — semua
  ikut dibersihkan; sisanya cascade/rollback-aman (transaksi gagal = tidak
  ada yang terhapus, terbukti saat `complaint_messages` menghadang).
- Hapus 6 akun + dependents dalam 1 transaksi (berhasil, 0 tersisa).
- Seeder diperbaiki agar tidak kembali: `UserSeeder` dikosongkan dari akun
  gmail; `SuperAdminSeeder` tanpa `sa@gmail.com`; `OwnerSeeder` resolve
  `owner@raliva.test` (buat bila belum ada); exclusion list
  `RalivaDemoSeeder` hanya `@raliva.test`.
- Sampingan (temuan saat verifikasi, bukan dari penghapusan):
  `ExampleTest` basi (route `/` kini redirect by design) → assert redirect;
  halaman tracking kini Inggris → assertion test disesuaikan;
  3 migrasi remote tertunda dijalankan sebelumnya.

## Verifikasi

- Suite penuh: **145 passed + 4 skipped + 1 risky** (skip = DB 1 toko +
  guard scope; risky = test komplain lama; bukan regresi).
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Sisa: manual cek laporan (1 Owner) + browser.
