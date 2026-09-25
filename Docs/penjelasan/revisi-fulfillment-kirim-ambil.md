# Penjelasan: Ubah Tipe Pengiriman (Diantar vs Ambil di Toko)

Untuk: Admin & Owner (nonteknis).

## Ringkasan

Keputusan **paket dikirim atau diambil sendiri** sekarang dipegang **Admin lewat menu Pesanan**, bukan Customer. Admin bisa mengubah tipe pengiriman sebuah pesanan kapan pun sebelum pesanan dikirim/selesai, lewat tombol **Alihkan** di daftar pesanan.

## Cara pakai (Admin)

1. Buka **Pesanan**. Di setiap baris ada tombol **Alihkan** (warna emas) untuk pesanan yang masih boleh diubah.
2. Klik **Alihkan** → muncul dialog **"Ubah Tipe Pengiriman"** dengan dua pilihan:
   - **Diantar (Kurir)** — paket dikirim ke alamat customer.
   - **Ambil di Toko** — customer ambil sendiri di toko.
   - **Batal** — tutup tanpa perubahan.
   - Pilihan yang sedang aktif tampil **tidak bisa diklik** (abu-abu).
3. Dialog juga menampilkan **efek perubahan** sebelum Anda konfirmasi.

## Aturan penting

| Situasi | Yang terjadi |
|---|---|
| Diantar → Ambil di Toko | **Ongkir dibatalkan (jadi Rp 0)** dan total pesanan ikut turun sebesar ongkir. Tercatat di log aktivitas. |
| Ambil di Toko → Diantar | Ongkir **tidak** dipulihkan otomatis (tetap Rp 0). Bila ada biaya kirim, atur terpisah via WhatsApp/admin. |
| Customer | Selalu **dinotifikasi** tiap perubahan (judul "Pesanan Diambil di Toko" atau "Pesanan Dikirim"). |
| Status pesanan | **Dikirim, Selesai, Dibatalkan** → tombol Alihkan hilang dan backend menolak. |
| Sudah ada resi/kurir aktif | **Ditolak** — tidak bisa diubah selama pengiriman berjalan. |
| Admin lintas toko | Pesanan di luar toko tugas Anda tetap tidak bisa diubah (sama seperti aksi lain). |

## Dampak otomatis ke layar lain (tanpa kerja tambahan)

- **Menu Pengiriman**: pesanan "Ambil" masuk antrean **Siap Diambil**; pesanan "Diantar" masuk antrean **Siap Dikirim** (tempat simpan resi).
- **Tracking Customer**: otomatis menampilkan "Siap Diambil + info toko (tanpa resi)" atau cabang kurir "Dikemas/Dikirim + resi".
- **Invoice Admin**: otomatis menulis "Ambil di toko" atau resi kurir.
- **Modal Tambah Pesanan**: label sudah diperjelas menjadi **Kirim (diantar kurir)** vs **Ambil di Toko (tanpa ongkir)**.

## Sisi teknis (Admin IT)

- Route: `POST admin/pesanan/{pesanan}/alih-fulfillment` → `DataPesananController@alihFulfillment`.
- Dalam satu transaksi + lock baris: cek status → cek shipment aktif → update `tipe_pesanan` → bila ke offline: `total_ongkir = 0`, `grand_total -= ongkir`, checkout ikut disesuaikan → `ActivityLogger` (`admin.order.fulfillment`) → `Notification` customer + notifikasi internal.
- Test: `tests/Feature/AdminFulfillmentSwitchTest.php` (6 test: void ongkir, balik ke kirim, status terlarang, shipment aktif, input salah, render tombol).
- Verifikasi: `php -l`, `route:list`, `view:cache/clear`, 6/6 test + regresi OrderPriorityCancel/OrderTrackingPickup/PengirimanModal (7/7) lulus.

## Catatan keputusan

- Label dialog mengikuti makna aplikasi: **Diantar = online**, **Ambil di Toko = offline** (badge "Offline" di daftar pesanan = pesanan ambil di toko).
- Refund otomatis tidak dilakukan saat ongkir dibatalkan — refund tetap lewat alur Admin/Owner yang sudah ada bila perlu.
- Verifikasi browser manual (online/offline × mobile/desktop) belum dicentang di plan.
