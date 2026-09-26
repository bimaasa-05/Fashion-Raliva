# Penjelasan: JS Produksi Mati + Backfill Bahan

Untuk: tim Produksi, Admin IT (nonteknis + teknis ringkas).

## Masalah

Tiga gejala sekaligus di halaman Data Produksi: tombol **+Bahan** dan **Selesai**
tidak bisa diklik, dan keterangan waktu macet di **"Memuat..."** terus.

## Akar masalah

File halaman me-include partial countdown (`<script>...</script>` utuh) **di dalam**
blok `<script>` halaman itu sendiri. Browser menutup script di tag penutup
pertama → seluruh JavaScript halaman gagal dibaca → semua tombol berbasis JS
mati dan countdown tidak pernah jalan. Halaman Owner polanya benar, hanya
halaman Produksi yang rusak. Bug pra-ada ini lolos karena test otomatis tidak
mengeksekusi JavaScript.

## Perbaikan

- Include countdown dipindah ke **luar** blok `</script>` (tirupola Owner).
- Halaman Produksi lain (QC, Selesai, Riwayat, Bahan) dipindai — bersih.
- Test penjaga: assertion struktur sumber blade (include harus di luar script)
  + render test modal bahan.

## Backfill bahan order lama

Order yang terlanjur di-accept sebelum fitur salin-resep tidak punya baris
bahan-admin. Kini ada tombol **"Lengkapi Bahan"** di halaman Data Produksi
(muncul hanya bila ada yang perlu): menyalin resep → bahan order, idempoten
(ditekan dua kali tidak ganda), scope toko, tercatat di log.

## Verifikasi

- Suite penuh: **136 passed + 2 skipped + 1 risky** (skip = DB 1 toko;
  risky = test komplain lama tanpa assertion; bukan regresi).
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- **Wajib**: cek manual di browser — klik +Bahan (modal terbuka + ada kotak
  "Bahan dibutuhkan"), klik Selesai, countdown berjalan, bahan tampil.
