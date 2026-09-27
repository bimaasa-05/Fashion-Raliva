# Penjelasan: Owner di Rekap Karyawan (Default)

Untuk: Owner (nonteknis).

## Ringkasan

Halaman Rekap Karyawan kini punya filter **Owner** — dan menjadi **default**
(bukan Admin lagi). Baris Owner = pemilik toko (`stores.owner_id`), metriksnya
**ROI + Pendapatan + Investasi + Bersih** mengikuti rumus yang disepakati
(ROI = laba bersih / total investasi × 100%).

## Detail

- Dropdown role: Owner paling atas dan terpilih otomatis saat halaman dibuka.
- Tabel (desktop), kartu HP, baris total, PDF, dan Excel: kolom ROI,
  Pendapatan, Investasi, Bersih + footnote definisi ROI.
- Filter Admin/Produksi/Gudang tidak berubah (termasuk CR penjualan + LTV
  Admin dari revisi sebelumnya).

## Verifikasi

- Suite penuh: **145 passed + 2 skipped + 1 risky** (skip = DB 1 toko;
  risky = test komplain lama; bukan regresi).
- Test: default = owner, baris owner berkunci ROI, render semua filter.
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Catatan data: DB lokal berubah (10 produk aktif, 0 grant) sehingga test
  pembuat-produk kini menyiapkan kuota sendiri (pola `ensureQuota`, rollback
  otomatis) agar tidak tergantung data.
- Sisa: verifikasi browser manual (filter owner, export excel/pdf).
