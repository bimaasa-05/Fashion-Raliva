# Revisi Produk, Warna, Checkout, dan Ajukan Perubahan

Tanggal keputusan: 2026-09-24.
Status dokumen: rencana disepakati, belum dieksekusi.

Scope: file Admin, ditambah pengecualian minimal berikut:

1. Tampilan Customer hanya untuk memperbaiki foto produk dan label warna yang rusak.
   Tidak ada perubahan logika keranjang, pembayaran, atau pembuatan order.
2. SuperAdmin hanya untuk halaman review “Ajukan Perubahan” produk.
   Tidak ada perubahan workflow SuperAdmin lainnya.

## Keputusan yang dikunci

- Foto yang hilang di halaman checkout diperlakukan sebagai bug rendering.
  Tidak dibuat relasi foto per warna/varian.
- Setiap revisi produk aktif oleh Admin menjadi proposal dan dikunci sampai SuperAdmin memutuskan.
- Warna custom wajib memiliki nama bermakna dan kode hex.
- Nama otomatis seperti “Warna 1” tidak boleh dibuat atau disimpan.
- Customer tidak lagi melihat inisial satu huruf sebagai representasi utama warna.

## Fakta saat ini

- `resources/views/customer/checkout/index.blade.php:659-660` memakai `asset($img)`.
- Foto Admin disimpan sebagai path storage, misalnya `products/...`.
- Helper resmi adalah `photo_url()` di `app/Support/photo_url.php:9-23`.
- Detail produk memakai helper tersebut, sehingga foto tampil di detail tetapi dapat rusak di checkout.
- `resources/views/customer/shop/produk-detail.blade.php:446-485` membangun peta hex dengan kunci huruf asli, tetapi mencarinya dengan `strtolower`.
- Filter toko memakai peta hex Inggris pada `resources/views/customer/shop/index.blade.php:428-440,622-624`.
- Payload edit Admin tidak membawa `warna_hex` tersimpan pada `resources/views/Admin/produk/index.blade.php:82`.
- Update Admin langsung menulis produk, foto, varian, dan stok pada `app/Http/Controllers/Admin/DataProdukController.php:204-331`.
- Moderasi produk baru/pending ada pada `app/Http/Controllers/SuperAdmin/ModerasiProdukController.php:15-68`.

## Paket 1 — Foto checkout

- [x] Ganti rendering foto checkout menjadi `photo_url($img)`.
- [x] Pertahankan fallback placeholder apabila produk tidak mempunyai foto.
- [x] Pastikan foto varian/produk yang dipilih tetap konsisten antara detail, checkout, order, dan tracking.
- [x] Tambahkan regresi untuk:
  - [x] Beli langsung.
  - [x] Item keranjang.
  - [x] Produk tanpa foto.
  - [x] Path storage dan URL absolut.

## Paket 2 — Normalisasi warna

- [x] Buat resolver warna terpusat dengan prioritas:
  1. `warna_hex` tersimpan di varian.
  2. Palet resmi tanpa memperhatikan kapitalisasi dan spasi.
  3. Fallback netral disertai nama lengkap warna.
- [x] Gunakan resolver tersebut di:
  - [x] Form tambah produk Admin.
  - [x] Form edit produk Admin.
  - [x] Detail produk Customer.
  - [x] Filter toko Customer.
- [x] Perbaiki lookup `strtolower` yang menyebabkan semua warna menjadi abu-abu.
- [x] Sertakan hex tersimpan dalam JSON varian yang dipakai form edit.
- [x] Tampilkan nama warna aktif secara eksplisit, misalnya `WARNA: MERAH`.
- [x] Hapus inisial satu huruf sebagai tampilan utama warna.
- [x] Backfill hex untuk nama warna yang sudah dikenali palet resmi.
- [x] Jangan mengubah nama custom yang tidak dikenal secara otomatis.

## Paket 3 — Validasi warna Admin

- [x] Warna preset tetap memakai nama dan hex resmi.
- [x] Warna custom wajib memenuhi:
  - [x] Nama minimal dua karakter dan bukan placeholder.
  - [x] Kode hex valid enam digit.
  - [x] Tidak ada nama kosong yang otomatis menjadi “Warna N”.
- [x] Validasi nama dan hex di frontend dan backend.
- [x] Jaga urutan `warna[]` dan `warna_hex[]` agar tidak tertukar.
- [x] Simpan nama resmi sebagaimana dimasukkan Admin dan hex ternormalisasi.
- [x] Tampilkan error yang menyebut warna bermasalah, bukan error umum.

## Paket 4 — Ajukan Perubahan ke SuperAdmin

- [x] Buat migrasi `product_update_requests` berisi:
  - [x] `product_id`.
  - [x] `store_id`.
  - [x] `requested_by`.
  - [x] Status `pending`, `disetujui`, atau `ditolak`.
  - [x] Snapshot sebelum dan sesudah perubahan.
  - [x] Penambahan dan penghapusan gambar yang diusulkan.
  - [x] `reviewed_by`.
  - [x] Alasan keputusan.
- [x] Ubah tombol edit produk aktif dari “Simpan Perubahan” menjadi “Ajukan Perubahan”.
- [x] Simpan gambar baru di lokasi staging sebelum disetujui.
- [x] Jangan menghapus gambar lama sebelum proposal disetujui.
- [x] Kunci produk yang mempunyai proposal pending.
- [x] Tampilkan badge “Menunggu keputusan SuperAdmin” di daftar dan form Admin.
- [x] Buat halaman review SuperAdmin berisi:
  - [x] Perbandingan sebelum dan sesudah.
  - [x] Pratinjau gambar lama dan baru.
  - [x] Perubahan varian, harga, stok, deskripsi, dan kategori.
  - [x] Tombol setujui dan tolak beralasan.
- [x] Terapkan perubahan yang disetujui memakai operasi update yang sama dengan update langsung saat ini.
- [x] Hapus file staging bila proposal ditolak atau digantikan.
- [x] Catat audit dan kirim notifikasi pengajuan, persetujuan, dan penolakan.

## Urutan eksekusi

1. Paket 1 dan Paket 2.
2. Paket 3.
3. Paket 4.

## Status eksekusi (2026-09-24)

- [x] Paket 1 selesai di kode dan test: rendering checkout memakai `photo_url()`, fallback dipertahankan,
  dan `CheckoutPhotoTest` lulus untuk beli langsung, keranjang, URL absolut, serta tanpa foto.
- [x] Paket 2 selesai di kode dan test: resolver warna terpusat, backfill hex, dan tampilan Customer/Admin.
- [x] Paket 3 selesai di kode dan test: validasi nama dan hex warna custom di frontend dan backend.
- [x] Paket 4 selesai di kode dan test: proposal terkunci, halaman review, persetujuan, penolakan, audit, dan notifikasi.
- [ ] Verifikasi browser manual belum dilakukan.
- [ ] Full suite menyisakan satu kegagalan lama di `DataPesananOrderStoreTest::test_online_order_cannot_be_finished_directly`
  yang tidak tersentuh perubahan dokumen ini.

## Verifikasi

- [x] `php -l` untuk controller yang diubah.
- [x] Migrasi naik dan rollback untuk tabel proposal.
- [x] `php artisan view:cache` lalu `view:clear`.
- [x] Test otomatis untuk foto, warna, validasi, proposal, review, persetujuan, dan penolakan.
- [ ] Verifikasi browser manual berikut:
  - [ ] Manual Admin:
    - [ ] Tambah warna custom tanpa nama ditolak.
    - [ ] Tambah warna custom tanpa hex valid ditolak.
    - [ ] Edit menampilkan warna tersimpan, bukan abu-abu.
    - [ ] Produk aktif tidak dapat diubah langsung.
  - [ ] Manual Customer:
    - [ ] Foto checkout tampil.
    - [ ] Warna Merah dan warna custom tampil benar.
    - [ ] Tidak ada inisial warna sebagai tampilan utama.
  - [ ] Manual SuperAdmin:
    - [ ] Proposal terlihat lengkap.
    - [ ] Persetujuan menerapkan semua perubahan.
    - [ ] Penolakan tidak mengubah produk aktif.

## Risiko

- Symlink atau file storage yang hilang tetap dapat menyebabkan foto rusak walaupun URL diperbaiki.
- File staging harus dibersihkan agar tidak menumpuk bila banyak proposal ditolak.
- Proposal yang dikunci dapat menghambat Admin bila antrean review SuperAdmin lama.
