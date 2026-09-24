# Revisi Fulfillment Kirim vs Ambil — Keputusan Admin

Tanggal keputusan: 2026-09-24 (revisi: pilihan fulfillment di Admin, bukan Customer).
Status dokumen: rencana disepakati, belum dieksekusi.

Scope: Admin pesanan + pengiriman, tracking read-only yang sudah ada. Customer tanpa layar baru.

## Keputusan yang dikunci

- Pilihan Kirim / Ambil dipegang **Admin**, bukan Customer.
- Admin **boleh mengubah** fulfillment order yang sudah ada (termasuk order dari checkout).
- Order berbayar yang dialihkan ke ambil: ongkir **void jadi 0** (`total_ongkir` + `grand_total` disesuaikan, dicatat di log). Tanpa refund otomatis.
- Dua tombol aksi eksisting menjadi wujud pilihan: **Simpan Resi / Tandai Dikirim** (diantar) vs **Siap Untuk Diambil / Selesai (Diambil)** (ambil di toko).
- Label Online/Offline di modal Admin diperjelas maknanya sebagai Kirim vs Ambil.

## Fakta saat ini

- Modal Tambah Pesanan Admin sudah punya radio Online/Offline (`Admin/pesanan/index.blade.php:383`), bermakna sumber order.
- `DataPesananController@store` memvalidasi `tipe_pesanan` online/offline; offline = tunai/transfer + `diambil_pada`.
- Tracking pickup (`isOffline()`) dan invoice ("Ambil di toko") sudah mengikuti `tipe_pesanan`.
- Belum ada aksi untuk mengubah `tipe_pesanan` order yang sudah ada.

## Paket 1 — Label dan modal Tambah Pesanan

- [x] Ganti label radio menjadi Kirim (Online) / Ambil di Toko (Offline) + helper text.
- [x] Pastikan perilaku backend `store()` tidak berubah.

## Paket 2 — Aksi alihkan fulfillment

- [x] Tombol alihkan Kirim↔Ambil di daftar pesanan (modal konfirmasi), hanya untuk status sebelum dikirim/selesai.
- [x] Endpoint backend: validasi scope toko + status, update `tipe_pesanan`.
- [x] Bila ke ambil: void `total_ongkir` jadi 0, recompute `grand_total`, catat log aktivitas.
- [x] Bila ke kirim: kembalikan ke alur kurir (input resi); ongkir lama tidak dipulihkan otomatis.
- [x] Notifikasi customer tiap pengalihan.
- [x] Larang alihkan bila sudah ada shipment aktif / sudah selesai / dibatalkan.

## Verifikasi

- [x] `php -l` untuk controller yang diubah.
- [x] `php artisan view:cache` lalu `view:clear`.
- [x] Test: online→ambil (ongkir 0, grand_total turun, tracking pickup).
- [x] Test: alihkan status terlarang ditolak.
- [ ] Manual Admin: radio jelas, tombol alihkan, tracking ikut berubah.

## Risiko

- Void ongkir mengubah grand_total order berbayar — wajib tercatat di log agar audit jelas.
- Alih ke kirim setelah void tidak memulihkan ongkir otomatis (keputusan sadar).
