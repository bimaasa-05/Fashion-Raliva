# Revisi Pesanan, Pengiriman, Tracking, dan Fulfillment

Tanggal keputusan: 2026-09-24.
Status dokumen: Paket 5 (tracking rekan kerja) + Paket 5b (pickup & hapus refund) selesai; Paket 1–4 dan 6 belum dieksekusi.

Scope: file Admin, Owner, dan Customer yang diminta. SuperAdmin tidak diubah.

## Keputusan yang dikunci

- `Menunggu Produksi` selalu tampil paling atas pada daftar pesanan.
- Tombol Batal disembunyikan untuk semua pembayaran terverifikasi.
- Aturan tersebut termasuk pesanan tunai offline yang langsung berstatus terverifikasi.
- Pengajuan refund oleh Customer dihapus total (tombol, modal, route `refund.store`, method `storeRefund`) — 2026-09-24.
  Status refund yang sudah ada tetap tampil sebagai riwayat; refund baru hanya bisa dibuat lewat alur Admin/Owner.
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
- Tracking Customer sudah memetakan status produksi, QC, dan siap kirim (pull rekan kerja 2026-09-24); cabang pickup ditangani di Paket 5b.
- Checkout belum mempunyai pilihan fulfillment terpisah.

## Paket 1 — Gaya input tanggal dan waktu

- [x] Reuse partial `custom-datepicker` di layout Admin (kalender + ikon + fokus/error bawaan partial).
- [x] Berlaku otomatis untuk `datetime-local` jadwal produksi dan `date` estimasi tiba (scan + MutationObserver).
- [x] Aturan validasi tanggal mulai dan berakhir tidak diubah.

## Paket 2 — Prioritas Menunggu Produksi

- [x] Scope `Order::prioritasStatus()` terpusat (`menunggu_produksi` rank 0, sisanya rank 1).
- [x] Diterapkan di daftar Admin (`DataPesananController@index`) dan Owner (`PesananController@index`).
- [x] Desktop dan mobile memakai koleksi `$orders` yang sama sehingga ikut prioritas.
- [x] Arti status operasional tidak diubah.

## Paket 3 — Batal hanya sebelum verifikasi

- [x] Helper `Order::isPaymentVerified()` (cek `checkout.payment.status === terverifikasi`).
- [x] Tombol + modal Batal disembunyikan di desktop dan mobile bila terverifikasi.
- [x] Guard backend di `batalkan()`: request langsung ditolak dengan pesan penjelasan.
- [x] Berlaku semua status dan metode, termasuk tunai offline (langsung terverifikasi saat dibuat).
- [x] Pengajuan refund Customer sudah dihapus total (lihat Paket 5b); pesanan terverifikasi diselesaikan lewat alur Admin/Owner.
- [x] Test `OrderPriorityCancelTest`: prioritas, tolak batal terverifikasi, batal pending tetap bisa.

## Paket 4 — Modal pengiriman

- [x] Pindahkan semua modal pengiriman keluar dari tabel dan kontainer scroll (loop bawah: kirim, selesai-ambil, confirm-resi).
- [x] Setiap ID modal didefinisikan tepat satu kali (verifikasi via grep).
- [x] Gunakan kembali sistem modal global + portal `ralivaOpenModal` menempelkan modal ke `body`.
- [x] Ganti `confirm()` mobile dengan tombol modal `modal-kirim-*` yang sama dengan desktop.
- [x] Test `PengirimanModalTest`: halaman render 200, tanpa `confirm()` native, modal kirim di luar `<td>`.
- [ ] Verifikasi browser manual: online/offline × mobile/tablet/desktop.

## Paket 5 — Progress tracking Customer

- [x] Perluas pemetaan status tracking untuk (dikerjakan rekan kerja, diverifikasi 2026-09-24):
  - [x] Pembayaran diterima.
  - [x] Menunggu produksi.
  - [x] Diproduksi.
  - [x] QC dan packing.
  - [x] Siap kirim (cabang kurir).
  - [ ] Siap diambil (cabang pickup) — dikerjakan di Paket 5b.
  - [x] Dikirim (cabang kurir).
  - [ ] Diambil (cabang pickup) — dikerjakan di Paket 5b.
  - [x] Selesai.
- [x] Timeline berbasis aksi role Produksi/Admin/Customer (rekan kerja).
- [ ] Tampilkan pesan berbeda untuk pengiriman kurir dan ambil di toko — dikerjakan di Paket 5b.
- [ ] Jangan menampilkan resi untuk pesanan ambil sendiri — dikerjakan di Paket 5b.
- [ ] Tampilkan instruksi pengambilan bila pesanan siap diambil — dikerjakan di Paket 5b.
- [x] Status refund dan pembatalan tetap tampil sebagai riwayat.

## Paket 5b — Cabang pickup tracking + hapus refund Customer (2026-09-24)

- [x] Timeline pickup: Disiapkan → Dikemas → Siap Diambil → Selesai Diambil (pakai `tipe_pesanan` yang ada).
- [x] Label status pickup: Siap Diambil / Selesai Diambil.
- [x] Pesan khusus pickup untuk siap_kirim, dikirim, dan selesai.
- [x] Sembunyikan resi/kurir untuk pesanan ambil; tampilkan toko + alamat + waktu diambil.
- [x] Tombol konfirmasi berbahasa pengambilan untuk pesanan ambil.
- [x] Hapus total pengajuan refund Customer: tombol + modal komplain, route `refund.store`, method `storeRefund`.

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

## Status eksekusi (2026-09-24)

- [x] Paket 1, 2, 3, 4 selesai di kode dan test otomatis.
- [ ] Verifikasi browser manual belum dilakukan.

## Verifikasi

- [x] `php -l` untuk controller yang diubah.
- [x] `php artisan view:cache` lalu `view:clear`.
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
