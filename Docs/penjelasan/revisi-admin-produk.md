# Penjelasan Revisi Admin Produk — Lengkap

Tanggal selesai: 2026-09-24.

## Untuk siapa dokumen ini

Dokumen ini terutama untuk **Admin Toko** yang menambah dan mengajukan perubahan produk.

> Cara membaca cepat 30 detik:
> - Foto tetap pertama.
> - Isi identitas produk, lalu rencana produksi, lalu varian dan stok.
> - Warna boleh kosong.
> - Produk baru menunggu moderasi Super Admin.
> - Perubahan produk aktif tidak langsung berlaku; harus diajukan dan disetujui dulu.
> - Semua Rupiah ditampilkan bulat, misalnya `Rp 10.000`.

Istilah penting:

- **Admin** = pengelola operasional toko.
- **Super Admin** = peninjau dan pemberi keputusan akhir.
- **Owner** = pemilik toko yang menerima notifikasi.
- **Customer** = pembeli di toko online.
- **Varian** = kombinasi ukuran dan warna yang bisa dibeli.
- **Resep** = daftar bahan untuk membuat satu unit produk.
- **Proposal** = pengajuan perubahan yang menunggu keputusan.

---

## 1. Gambaran alur produk

```text
Admin tambah produk
 └─> produk berstatus pending
 └─> Super Admin memeriksa produk baru
 └─> disetujui: produk aktif dan bisa dibeli
 └─> ditolak: Admin memperbaiki lalu mengajukan ulang
```

```text
Admin ubah produk aktif
 └─> perubahan disimpan sebagai proposal
 └─> produk dikunci selama proposal pending
 └─> Super Admin membandingkan sebelum dan sesudah
 └─> disetujui: perubahan berlaku
 └─> ditolak: produk tetap seperti semula
```

Produk baru tidak langsung tampil untuk Customer. Perubahan produk aktif juga tidak langsung berlaku.

---

## 2. Urutan form tambah dan edit produk

Form tambah dan edit memakai urutan yang sama:

1. Foto Produk.
2. Informasi Dasar:
   - Nama Produk.
   - Kategori dan Tipe Produk.
   - Harga.
   - Deskripsi.
3. Rencana Produksi:
   - Target dan biaya operasional.
   - Bahan produksi.
   - Ringkasan modal dan margin.
4. Variasi & Stok:
   - Ukuran.
   - Warna opsional.
   - Stok per varian.

Tujuannya mengikuti cara kerja nyata: kenalkan produk, rencanakan pembuatannya, lalu atur varian jualannya.

---

## 3. Foto produk

Aturan foto:

- Minimal 1 foto.
- Maksimal 5 foto.
- Format yang diterima: JPG, JPEG, PNG, atau WebP.
- Ukuran tiap file maksimal 2 MB.
- Foto pertama menjadi foto utama.

Saat edit produk:

- Centang foto lama bila ingin menghapusnya.
- Foto baru hanya diusulkan dulu, belum langsung tersimpan sebagai foto resmi.
- Foto lama baru dihapus setelah proposal disetujui.
- Total foto lama yang dipertahankan ditambah foto baru tidak boleh lebih dari 5.

Foto yang tampil di checkout memakai foto produk yang sama dengan foto di detail produk. Sistem tidak memiliki foto khusus per warna.

---

## 4. Informasi dasar produk

### 4.1 Nama produk

Nama wajib diisi, maksimal 255 karakter.

Contoh:

- `Blazer Wool Premium`

### 4.2 Kategori dan tipe produk

Kategori wajib dipilih. Tipe produk hanya boleh:

- Regular.
- Preorder.
- Made to Order.

### 4.3 Harga dasar

Harga memakai prefix `Rp` dan format ribuan.

Contoh input:

- `949.000`

Contoh yang tidak perlu ditulis:

- `949.000,00`

Harga dasar minimal Rp1 dan maksimal Rp999.999.999.999.

### 4.4 Deskripsi

Deskripsi wajib minimal 10 karakter dan maksimal 2.000 karakter.

Contoh kurang jelas:

- `Blazer bagus.`

Contoh lebih jelas:

- `Blazer wool premium dengan potongan slim fit, lapisan dalam adem, dan jahitan rapi untuk acara formal.`

---

## 5. Rencana produksi

### 5.1 Target produksi

Target produksi adalah jumlah unit yang direncanakan dibuat dalam satu batch.

Aturan:

- Wajib diisi saat tambah produk.
- Minimal 1 unit.
- Maksimal 1.000.000 unit.
- Ditampilkan dengan format ribuan, misalnya `10.000`.

Target produksi bukan stok otomatis. Stok tetap diatur per varian di bagian Variasi & Stok.

### 5.2 Biaya operasional per unit

Biaya operasional adalah biaya produksi lain **di luar bahan pada resep**, diisi sebagai baris-baris terpisah. Setiap baris hanya berisi nama biaya dan harga per unit.

Contoh baris biaya operasional:

- Ongkos jahit — Rp5.000.
- Kemasan — Rp2.000.

Satu produk boleh memiliki banyak baris biaya; totalnya dijumlahkan otomatis.

```text
total operasional = jumlah seluruh harga operasional per unit
modal per unit = modal bahan + total operasional
```

Biaya operasional boleh dikosongkan seluruhnya, tetapi setiap baris yang diisi wajib memiliki nama dan nominal tidak negatif.

Contoh biaya bahan:

- Kain Rp5.000 per meter.
- Kancing Rp500 per buah.

Biaya bahan diisi di baris resep, bukan di baris biaya operasional.

### 5.3 Mengisi bahan produksi

Klik **Tambah Bahan**, lalu isi:

1. Bahan katalog bila tersedia, atau nama bahan manual.
2. Satuan dari daftar resmi:
   - meter, cm, yard, roll, kg, gram, atau pcs.
3. Jumlah per unit.
4. Biaya per unit.

Aturan bahan:

- Setiap produk wajib memiliki minimal satu bahan.
- Nama bahan maksimal 150 karakter.
- Jumlah minimal 0,001.
- Biaya boleh nol, tetapi tidak boleh negatif.
- Maksimal 50 baris bahan.

Nama bahan dari katalog tetap disimpan sebagai snapshot. Artinya, bila master bahan diubah belakangan, resep lama tidak ikut berubah.

### 5.4 Ringkasan modal dan margin

Rumus yang dipakai:

```text
modal bahan = jumlah seluruh(jumlah per unit x biaya per unit)
modal per unit = modal bahan + total operasional per unit
modal batch = modal per unit x target produksi
margin per unit = harga jual - modal per unit
margin persen = margin per unit / harga jual x 100%
```

Contoh bahan 2 meter x Rp5.000, operasional Rp2.000 (misalnya Ongkos jahit), target 10, harga Rp25.000:

- Modal bahan: Rp10.000.
- Modal per unit: Rp12.000.
- Modal batch: Rp120.000.
- Margin: Rp13.000.

Contoh lain bila total operasional Rp2.000 dan modal bahan Rp13.000:

- Modal per unit: Rp15.000.
- Modal batch untuk target 10: Rp150.000.
- Margin untuk harga Rp25.000: Rp10.000 atau 40%.

Ringkasan di layar hanya pratinjau. Server menghitung ulang semuanya sebelum data disimpan.

---

## 6. Variasi, warna, ukuran, dan stok

### 6.1 Ukuran

Admin wajib memilih minimal satu ukuran.

Ukuran bisa berasal dari:

- Pilihan kategori toko.
- Pilihan bawaan XS sampai XXL atau All Size.
- Ukuran custom berisi detail seperti lingkar dada dan panjang baju.

### 6.2 Warna boleh kosong

Warna tidak wajib. Produk boleh hanya memiliki ukuran.

Bila warna dikosongkan:

- Varian disimpan tanpa warna, bukan otomatis menjadi Hitam.
- Customer tidak melihat pemilih warna.
- Customer langsung memilih ukuran.

Bila warna diisi, aturannya:

- Boleh memilih lebih dari satu warna.
- Warna preset memakai nama dan kode warna resmi.
- Warna custom wajib memiliki nama bermakna, minimal 2 karakter.
- Nama seperti `Warna 1` tidak boleh dipakai.
- Warna custom wajib memiliki kode hex valid 6 digit.
- Customer melihat nama warna lengkap, bukan inisial satu huruf.

### 6.3 Stok per varian

Setiap kombinasi ukuran dan warna harus memiliki stok.

Aturan stok produk baru:

- Stok minimal 1 per varian.
- Ambang menipis wajib diisi.
- Stok disimpan di gudang aktif toko.
- Bila toko belum punya gudang aktif, sistem membuat Gudang Utama.

Varian lama yang sudah memiliki order tidak dihapus otomatis. Kombinasi baru dibuat bila Admin menambah ukuran atau warna baru.

Perubahan harga dasar pada proposal yang disetujui juga memperbarui harga varian yang bersangkutan.

---

## 7. Format angka

Aturan tampilan:

- Semua Rupiah memakai bilangan bulat Indonesia.
- Contoh: `Rp 10.000`, bukan `Rp 10.000,00`.
- Semua input Rupiah memakai prefix `Rp`.
- Angka besar memakai pemisah ribuan.
- Jumlah bahan boleh memakai desimal bila berarti, misalnya `2,5`.
- Persen margin boleh memakai desimal bila berarti.

Contoh input yang benar:

- Target: `10.000`
- Biaya operasional per baris, misalnya Ongkos jahit: `5.000`
- Biaya bahan: `5.000`
- Jumlah bahan: `2` atau `2,5`

Backend menyimpan angka tanpa pemisah ribuan. Jadi `10.000` tersimpan sebagai `10000`.

---

## 8. Validasi dan pesan error yang umum

| Gejala | Artinya | Yang harus dilakukan |
|---|---|---|
| `Pilih minimal 1 warna.` pada versi lama | Aturan lama mewajibkan warna | Versi sekarang membolehkan warna kosong; isi ukuran dan stok saja |
| `Nama warna wajib diisi minimal 2 karakter.` | Warna custom belum diberi nama | Isi nama bermakna, misalnya `Tosca` |
| `Berikan nama warna yang bermakna, misalnya Tosca.` | Nama memakai pola `Warna 1` | Ganti dengan nama warna sebenarnya |
| `Warna custom ... wajib memiliki kode hex.` | Warna baru belum punya kode warna | Isi hex valid, misalnya `f4f4f4` |
| `Isi minimal 1 bahan produksi.` | Resep masih kosong | Klik Tambah Bahan dan isi minimal satu bahan |
| `Target produksi minimal 1.` | Target nol atau kosong | Isi target minimal 1 |
| `Maksimal total 5 foto ...` | Foto lama ditambah foto baru melebihi 5 | Hapus dulu foto lama yang tidak dipakai |
| `Kuota slot produk penuh ...` | Slot produk toko habis | Ajukan pembelian slot dulu di menu Beli Slot |
| `Produk ini sudah mempunyai pengajuan ...` | Masih ada proposal pending | Tunggu keputusan Super Admin sebelum mengajukan lagi |

---

## 9. Setelah produk disimpan

Produk baru langsung berstatus pending dengan keterangan menunggu moderasi Super Admin.

Sistem mengirim:

- Notifikasi ke Super Admin.
- Notifikasi pengingat ke Admin pengaju.

Produk menunggu keputusan sebelum tampil sebagai produk aktif.

Bila kuota slot toko penuh, produk tidak bisa diajukan. Admin harus menambah slot dulu.

---

## 10. Mengubah produk aktif

Tombol edit produk aktif bernama **Ajukan Perubahan**, bukan Simpan Perubahan.

Alurnya:

1. Admin mengubah teks, foto, varian, stok, target, biaya operasional, atau resep.
2. Gambar baru disimpan sementara di area staging.
3. Gambar lama belum dihapus.
4. Resep lama belum diganti.
5. Sistem membuat proposal pending.
6. Produk dikunci dan menampilkan badge pengajuan pending.
7. Admin tidak bisa mengajukan perubahan kedua sebelum proposal pertama diputuskan.

Notifikasi pengajuan dikirim ke:

- Super Admin.
- Owner toko.
- Admin pengaju.

---

## 11. Review Super Admin

Halaman review menampilkan status pending, disetujui, dan ditolak.

Untuk setiap proposal, Super Admin melihat:

- Data utama sebelum dan sesudah.
- Target, biaya operasional, modal, dan margin lama versus baru.
- Foto lama versus foto usulan.
- Foto yang diusulkan untuk dihapus.
- Varian, harga, dan stok lama versus baru.
- Resep yang ditambah, diubah, atau dihapus.
- Pengaju dan waktu pengajuan.

### 11.1 Bila disetujui

- Semua perubahan proposal berlaku sekaligus.
- Foto staging dipindahkan menjadi foto resmi.
- Foto lama yang dipilih ikut dihapus.
- Varian dan stok diperbarui.
- Target, biaya operasional, modal, dan resep diperbarui.
- Status proposal menjadi disetujui.
- Admin, Owner, dan Super Admin menerima notifikasi.

### 11.2 Bila ditolak

- Produk tetap seperti semula.
- Foto staging dihapus.
- Proposal mendapat alasan penolakan minimal 10 karakter.
- Admin dan Owner menerima alasan tersebut.

---

## 12. Dampak ke Customer

Perubahan produk memengaruhi Customer dengan cara berikut:

- Foto checkout memakai foto produk resmi yang sama dengan detail produk.
- Bila produk tidak punya foto, checkout menampilkan placeholder.
- Warna preset dan custom tampil dengan warna yang benar.
- Nama warna lengkap ditampilkan, bukan inisial.
- Produk tanpa warna menyembunyikan pemilih warna.
- Customer tetap memilih ukuran lalu menambah varian ke keranjang.
- Alur keranjang, pembayaran, dan pembuatan order tidak diubah oleh revisi ini.

---

## 13. Batasan yang masih berlaku

- Foto tidak bisa dipasangkan khusus per warna; foto berlaku untuk seluruh produk.
- Resep hanya acuan perencanaan dan tidak mengurangi stok otomatis.
- Perubahan harga bahan di katalog tidak mengubah resep lama karena nama bahan di-snapshot.
- Target produksi tidak selalu sama dengan total stok varian bila stok diubah manual.
- Proposal yang lama diputuskan dapat menghambat perubahan berikutnya.
- Foto bisa tetap rusak bila file storage hilang, meskipun URL-nya sudah benar.
- Verifikasi browser manual masih menjadi pekerjaan lanjutan.

---

## Apendiks teknis

### A. Tabel dan kolom penting

- `products`:
  - `nama_produk`, `deskripsi`, `harga_dasar`
  - `target_produksi`, `modal_produksi`, `biaya_tambahan`
  - `category_id`, `tipe_produk`, `status`, `alasan_penolakan`
- `product_variants`:
  - `product_id`, `sku`, `ukuran`
  - `warna` boleh null
  - `warna_hex` boleh null
  - `harga`, `status`
- `product_images`:
  - `product_id`, `file_gambar`, `urutan`
- `product_material_requirements`:
  - `product_id`
  - `material_id` opsional
  - `nama_bahan`, `satuan`
  - `jumlah_per_unit`, `biaya_per_unit`
- `product_update_requests`:
  - `product_id`, `store_id`
  - `requested_by`, `reviewed_by`
  - `status`: `pending`, `disetujui`, atau `ditolak`
  - `before_snapshot`, `after_payload`
  - `remove_image_ids`, `staged_images`
  - `review_note`

### B. Alamat penyimpanan file

- Foto resmi: `products/...`
- Foto usulan sebelum disetujui: `pending-product-updates/{token}/...`
- Foto usulan yang ditolak dihapus dari storage.
- Foto resmi yang diganti dipindahkan dari staging ke `products/...`.

### C. Route penting

- `admin.produk`
- `admin.produk.store`
- `admin.produk.update`
- `superadmin.moderasi-produk`
- `superadmin.perubahan-produk`
- `superadmin.perubahan-produk.setujui`
- `superadmin.perubahan-produk.tolak`
- `customer.shop.produk-detail`
- `customer.checkout`
- `customer.cart.add`

### D. Test otomatis terkait

- `CheckoutPhotoTest`: 4 test.
- `ProductColorTest`: 4 test.
- `ProductColorValidationTest`: 4 test.
- `ProductUpdateWorkflowTest`: 7 test.
- `ProductCostCalculatorTest`: 2 test.
- `ProductRecipeTest`: 4 test.
- `ProductFormPolishTest`: 3 test.

Total test produk terkait: 28 test.
