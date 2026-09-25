# Penjelasan: Tanggapan Admin atas QC Gagal ("Menunggu Admin")

Untuk: Admin, Owner, dan tim Produksi (nonteknis).

## Masalah yang diselesaikan

Sebelumnya, ketika tim Produksi menekan "Gagal — Hubungi Admin", pesanan hanya ditandai "Menunggu Admin" — tetapi Admin **tidak bisa melihat dan tidak bisa berbuat apa-apa**: tidak ada filter, tidak ada tombol, notifikasi hanya bunyi sekali. Kalau Admin diam, pesanan macet selamanya.

Sekarang loop-nya tertutup: Admin melihat → Admin memilih → Produksi diberitahu → kalau Admin diam, sistem mengingatkan.

## 1. Admin bisa melihat

- Badge merah **"QC Gagal"** di baris pesanan (tabel desktop + kartu HP), lengkap dengan keterangan dari tim Produksi (arahkan kursor/ketuk untuk membaca).
- Filter status baru **"Menunggu QC"** di halaman Data Pesanan — sebelumnya status ini bahkan tidak bisa difilter.
- Notifikasi "QC Gagal" sekarang membuka halaman pesanan **yang sudah terfilter** ke order tersebut, bukan halaman umum.

## 2. Admin bisa memilih (dua tombol)

Buka order bertanda → tombol **"Tanggapi QC"** → dialog berisi keterangan Produksi + kolom catatan opsional + dua pilihan:

| Pilihan | Artinya | Efek |
|---|---|---|
| **Rework Produksi** | Barang gagal, buat ulang | Pesanan kembali ke antrean produksi; tim Produksi se-toko dinotifikasi beserta catatan Admin |
| **Lanjut QC** | Boleh lanjut, periksa ulang saja | Pesanan tetap di QC; tim Produksi se-toko dinotifikasi untuk QC ulang |

Tidak ada pilihan Batalkan — pembatalan tetap mengikuti aturan pembayaran yang berlaku. Tombol hanya muncul untuk order yang memang bertanda; order toko lain tidak bisa ditanggapi.

## 3. Kalau Admin diam: pengingat otomatis

- Setiap pagi jam 09:00, sistem memeriksa: order bertanda QC Gagal yang **sudah lebih dari 24 jam tanpa tanggapan** → Admin se-toko menerima **notifikasi pengingat ulang**.
- Setelah Admin menanggapi (salah satu tombol), pengingat untuk order itu **berhenti sendiri**.
- Dashboard Admin menampilkan banner merah **"QC Menunggu Tanggapan: N"** + entri di daftar "Pekerjaan Tertunda", keduanya link langsung ke filter Menunggu QC.

## Sisi teknis (Admin IT)

- Route baru: `POST admin/pesanan/{pesanan}/qc-tanggapan` → `DataPesananController@qcTanggapan` (validasi: penanda ada + status `menunggu_qc` + scope toko via `AdminContext`; log `admin.order.qc-tanggapan`).
- Command baru: `php artisan qc:remind` (terdaftar `dailyAt('09:00')` di `routes/console.php`).
- Dashboard: stat `qc_perlu_admin` di `DashboardOperasionalController` + banner dan item pekerjaan (hanya tampil bila > 0).
- Test: `tests/Feature/AdminQcTanggapanTest.php` — 8 test (badge + tombol, filter, rework, lanjut, tolak tanpa penanda, tolak luar scope, pengingat >24 jam saja, pengingat berhenti setelah ditanggapi).
- Verifikasi: `php -l`, `view:cache/clear`, 8/8 test + regresi 43/43 lulus, `git diff --check` bersih.
- **Prasyarat server**: scheduler Laravel harus berjalan (`php artisan schedule:run` tiap menit via cron/Task Scheduler). Tanpa itu, pengingat jam 09:00 tidak terkirim. Cek: `php artisan schedule:list` harus memuat `qc:remind`.

## Belum dikerjakan

- Verifikasi browser manual (badge, filter, dialog rework/lanjut, banner dashboard, mobile).
- Eskalasi ke Owner bila Admin diam berhari-hari (disepakati: belum perlu).
