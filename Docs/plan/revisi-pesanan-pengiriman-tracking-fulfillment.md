# Revisi Pesanan, Pengiriman, Tracking, dan Fulfillment

Tanggal keputusan: 2026-09-24.
Status dokumen: rencana disepakati, belum dieksekusi.

Scope: file Admin, Owner, dan Customer yang diminta. SuperAdmin tidak diubah.

## Keputusan yang dikunci

- `Menunggu Produksi` selalu tampil paling atas pada daftar pesanan.
- Tombol Batal disembunyikan untuk semua pembayaran terverifikasi.
- Aturan tersebut termasuk pesanan tunai offline yang langsung berstatus terverifikasi.
- Pesanan terverifikasi memakai jalur refund, bukan pembatalan langsung.
- Input tanggal dan waktu tetap memakai kontrol native yang diberi gaya.
- Semua modal pengiriman harus memakai pola modal standar.
- Bagian fulfillment kirim/ambil masih tahap perencanaan dan belum boleh diimplementasikan.

## Fakta saat ini

- Daftar Admin diurutkan berdasarkan `updated_at DESC` tanpa prioritas status.
- Daftar Owner memakai `latest()`.
- Backend masih mengizinkan pembatalan sampai status diproses.
- Pesanan tunai offline langsung membuat pembayaran terverifikasi.
- Modal `Tandai Dikirim` berada di dalam sel tabel.
- Terdapat duplikat ID modal edit resi.
- Mobile memakai `confirm()` bawaan browser.
- Tracking Customer belum memetakan status produksi, QC, dan siap kirim.
- Checkout belum mempunyai pilihan fulfillment terpisah.

## Paket 1 — Gaya input tanggal dan waktu

- [ ] Bungkus `datetime-local` jadwal produksi dengan gaya tanggal/waktu yang konsisten.
- [ ] Tambahkan label, ikon, helper, status fokus, dan status error.
- [ ] Tampilkan pratinjau jadwal produksi yang mudah dibaca.
- [ ] Jangan mengubah aturan validasi tanggal mulai dan berakhir.
- [ ] Terapkan gaya yang sama pada input estimasi tiba pengiriman.

## Paket 2 — Prioritas Menunggu Produksi

- [ ] Buat helper prioritas status terpusat.
- [ ] Beri rank tertinggi untuk `menunggu_produksi`.
- [ ] Status lainnya tetap diurutkan berdasarkan pembaruan terbaru.
- [ ] Terapkan helper tersebut pada daftar Admin.
- [ ] Terapkan helper tersebut pada daftar Owner.
- [ ] Pastikan prioritas berlaku pada tampilan desktop dan mobile.
- [ ] Jangan mengubah arti status operasional yang sudah ada.

## Paket 3 — Batal hanya sebelum verifikasi

- [ ] Buat helper `isPaymentVerified()` untuk order.
- [ ] Sembunyikan tombol Batal pada desktop dan mobile bila pembayaran terverifikasi.
- [ ] Perkuat controller pembatalan agar request langsung juga ditolak.
- [ ] Berlakukan aturan tersebut untuk semua status dan semua metode pembayaran.
- [ ] Termasuk pesanan tunai offline sesuai keputusan.
- [ ] Pertahankan tombol dan alur refund yang sudah ada.
- [ ] Tambahkan pesan error yang menjelaskan bahwa pesanan terverifikasi tidak dapat dibatalkan langsung.

## Paket 4 — Modal pengiriman

- [ ] Pindahkan semua modal pengiriman keluar dari tabel dan kontainer scroll.
- [ ] Hapus duplikat ID modal edit resi.
- [ ] Gunakan kembali sistem modal global.
- [ ] Tambahkan portal/modal ke `body` sebagai pengaman bila dialog masih bersarang.
- [ ] Ganti `confirm()` mobile dengan modal konfirmasi standar.
- [ ] Pastikan dialog tidak terpotong atau berpindah posisi.
- [ ] Uji:
  - [ ] Pesanan online.
  - [ ] Pesanan offline.
  - [ ] Tombol `Tandai Dikirim`.
  - [ ] Edit resi.
  - [ ] Konfirmasi selesai diambil.
  - [ ] Lebar mobile, tablet, dan desktop.

## Paket 5 — Progress tracking Customer

- [ ] Perluas pemetaan status tracking untuk:
  - [ ] Pembayaran diterima.
  - [ ] Menunggu produksi.
  - [ ] Diproduksi.
  - [ ] QC dan packing.
  - [ ] Siap kirim atau siap diambil.
  - [ ] Dikirim atau diambil.
  - [ ] Selesai.
- [ ] Tampilkan pesan berbeda untuk pengiriman kurir dan ambil di toko.
- [ ] Jangan menampilkan resi untuk pesanan ambil sendiri.
- [ ] Tampilkan instruksi pengambilan bila pesanan siap diambil.
- [ ] Pertahankan status refund dan pembatalan yang sudah ada.

## Paket 6 — Perencanaan fulfillment, belum implementasi

- [ ] Dokumentasikan dua kandidat berikut tanpa mengeksekusi:
  1. Pilihan di checkout sebelum pembayaran.
  2. Pilihan di order tracking setelah pesanan dibuat.
- [ ] Kelebihan kandidat checkout:
  - [ ] Ongkir dapat dihitung sejak awal.
  - [ ] Alamat hanya wajib untuk pengiriman.
  - [ ] Validasi pembayaran lebih konsisten.
- [ ] Kelebihan kandidat tracking:
  - [ ] Tidak mengubah alur checkout lama.
  - [ ] Cocok bila keputusan fulfillment boleh berubah.
- [ ] Rancang kolom netral `fulfillment` dengan nilai `kirim` atau `ambil`.
- [ ] Rancang metadata pengambilan, misalnya lokasi, kontak, dan kode pengambilan.
- [ ] Tangani checkout multi-toko karena fulfillment dapat berbeda per order/toko.
- [ ] Jangan membuat migrasi fulfillment sebelum lokasi tombol diputuskan.

## Urutan eksekusi

1. Paket 1, 2, dan 3.
2. Paket 4.
3. Paket 5.
4. Paket 6 tetap sebagai dokumen sampai ada keputusan lokasi tombol.

## Verifikasi

- [ ] `php -l` untuk controller yang diubah.
- [ ] `php artisan view:cache` lalu `view:clear`.
- [ ] Manual Admin:
  - [ ] Menunggu produksi selalu di atas.
  - [ ] Tombol Batal hilang untuk pembayaran terverifikasi.
  - [ ] Request pembatalan langsung ditolak backend.
  - [ ] Modal pengiriman stabil.
- [ ] Manual Customer:
  - [ ] Timeline produksi sampai selesai dapat dipahami.
  - [ ] Pesanan ambil tidak meminta resi.
- [ ] Tambahkan atau perbarui test untuk prioritas, pembatalan terverifikasi, dan tracking.

## Risiko

- Menghilangkan Batal untuk tunai offline berarti kesalahan input kasir harus diselesaikan lewat refund.
- Perubahan urutan dapat mengejutkan Admin bila mereka terbiasa memakai urutan pembaruan murni.
- Modal yang dipindahkan membutuhkan pengujian ulang karena banyak ID modal dibuat per baris.
