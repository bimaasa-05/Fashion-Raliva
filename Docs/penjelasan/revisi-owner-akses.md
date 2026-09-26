# Penjelasan: Owner di Rekap (Bug) + Co-Access

Untuk: Owner + Admin IT (nonteknis + ringkas teknis).

## Bug: Owner tak muncul di Rekap (salah saya)

Blok baris Owner tertinggal **setelah `return`** sehingga tak pernah jalan —
halaman hanya menampilkan badge kosong. Test saya pun lolos vakum (hanya cek
kata "ROI" yang memang ada di kartu). Diperbaiki + test diperketat (assert
**nama pemilik** tampil).

## Akses rekan kerja (akun Owner tanpa toko)

Fakta data: hanya 1 akun yang memiliki toko; akun Owner lain tak punya toko
maupun penugasan — sehingga semua halaman Owner kosong + tombol export
terkunci. Itu by-design untuk akun tanpa toko, tapi tak memberi jalan keluar.

Perbaikan (sesuai keputusan: coworker harus bisa akses toko 3):

- Scope pemilik tanpa toko kini **fallback ke penugasan aktifnya** (pola Admin),
  sehingga seluruh halaman Owner (dashboard, rekap, laporan, export, keuangan)
  ikut bisa. Pengajuan toko tetap memakai kepemilikan murni (tak tersentuh).
- `SaldoController` diseragamkan memakai `OwnerContext::currentStore()`.
- **Data**: assignment aktif coworker (user 6) → toko 3 (reversibel, 1 baris).
- Akun yang benar-benar tanpa toko maupun assignment tetap terkunci sopan
  (halaman "Belum punya toko", export excel menolak dengan pesan).

## Verifikasi

- Suite penuh: **147 passed + 2 skipped + 1 risky** (skip = DB 1 toko;
  risky = test komplain lama; bukan regresi).
- Test baru: co-access bisa buka rekap + export; yatim tetap terkunci.
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Sisa: manual 2 akun (pemilik: baris ROI tampil; coworker: rekap + export
  PDF/Excel jalan).
