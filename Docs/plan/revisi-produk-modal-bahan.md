# Revisi Modal dan Resep Bahan Produk

Tanggal keputusan: 2026-09-24.
Status dokumen: rencana disepakati, belum dieksekusi.

Scope: file Admin dan migrasi produk. Tidak ada perubahan alur Produksi,
Gudang, atau Customer dalam paket ini.

## Keputusan yang dikunci

- Admin mengisi **biaya produksi dan target produksi**.
- Setiap produk mempunyai resep bahan per unit.
- Ringkasan modal, harga jual, dan margin harus terlihat oleh Admin.
- Resep hanya acuan perencanaan dan tidak langsung mengurangi stok.
- Perubahan resep pada produk aktif mengikuti mekanisme “Ajukan Perubahan”.

## Fakta saat ini

- Produk belum mempunyai kolom modal, target produksi, atau resep.
- Bahan yang ada berada di level order melalui `ProductionOrderBahan`.
- Katalog bahan tersedia melalui `BahanProduksi`.
- Inventaris bahan terpisah memakai `ProductionMaterial`.
- Harga jual memakai `harga_dasar` produk dan harga varian.

## Paket 1 — Skema resep dan modal

- [x] Buat migrasi `product_material_requirements` berisi:
  - [x] `product_id`.
  - [x] `material_id` opsional ke katalog bahan.
  - [x] Snapshot `nama_bahan`.
  - [x] `satuan`.
  - [x] `jumlah_per_unit`.
  - [x] `biaya_per_unit`.
- [x] Tambahkan profil produksi pada produk:
  - [x] `target_produksi`.
  - [x] `modal_produksi`.
  - [x] Biaya tambahan per unit bila diperlukan.
- [x] Gunakan kembali daftar satuan `ProductionOrderBahan::SATUAN`.
- [x] Hitung ulang modal di server, jangan hanya mengandalkan perhitungan JavaScript.

## Paket 2 — Rumus yang dipakai

Gunakan rumus berikut:

```text
modal_per_unit = jumlah seluruh(qty_per_unit x biaya_per_unit)
                 + biaya_tambahan_per_unit

modal_batch = modal_per_unit x target_produksi

margin_per_unit = harga_dasar - modal_per_unit

margin_persen = margin_per_unit / harga_dasar x 100%
```

- [x] `target_produksi` minimal 1.
- [x] Setiap bahan wajib memiliki nama, satuan, jumlah, dan biaya.
- [x] Setiap produk wajib memiliki minimal satu bahan.
- [x] Jaga pembulatan desimal untuk qty dan Rupiah.
- [x] Tolak pembagi nol pada perhitungan margin persen.

## Paket 3 — Form tambah produk

- [x] Tambahkan section “Rencana Produksi” pada modal tambah produk Admin.
- [x] Sediakan input target produksi dan biaya tambahan.
- [x] Sediakan baris bahan dinamis:
  - [x] Pilihan bahan dari katalog.
  - [x] Nama bahan manual.
  - [x] Jumlah per unit.
  - [x] Satuan.
  - [x] Biaya per unit.
- [x] Tampilkan ringkasan live:
  - [x] Modal per unit.
  - [x] Modal batch.
  - [x] Harga jual.
  - [x] Margin dan persentase.
- [x] Simpan resep dan profil produksi dalam transaksi yang sama dengan produk.

## Paket 4 — Tampilan dan proposal perubahan

- [x] Tampilkan tabel bahan dan ringkasan modal pada detail produk Admin.
- [x] Sertakan resep dan profil produksi dalam snapshot “Ajukan Perubahan”.
- [x] Tampilkan perbedaan resep lama dan baru pada review SuperAdmin.
- [x] Jangan menghapus resep lama sebelum proposal disetujui.

## Paket 5 — Urutan form dan format angka

- [x] Susun ulang form tambah dan edit menjadi Foto, Informasi Dasar, Rencana Produksi, Variasi & Stok.
- [x] Susun Informasi Dasar menjadi Nama, Kategori + Tipe, Harga, lalu Deskripsi.
- [x] Pakai prefix `Rp` untuk semua input Rupiah mengikuti pola Harga.
- [x] Tampilkan format ribuan `10.000` untuk angka dan Rupiah bulat.
- [x] Normalisasi format sebelum validasi backend.
- [x] Tambahkan regression test urutan form dan normalisasi angka.

## Status eksekusi (2026-09-24)

- [x] Paket 1 sampai Paket 5 selesai di kode dan test otomatis.
- [ ] Verifikasi browser manual belum dilakukan.

## Verifikasi

- [x] `php -l` untuk controller yang diubah.
- [x] Migrasi naik dan rollback.
- [x] `php artisan view:cache` lalu `view:clear`.
- [x] Test otomatis untuk skema, rumus, validasi, detail, proposal, review resep, urutan form, dan normalisasi angka.
- [ ] Verifikasi browser manual berikut:
  - [ ] Manual:
    - [ ] Produk tanpa bahan ditolak.
    - [ ] Target nol ditolak.
    - [ ] Biaya bahan kosong ditolak.
    - [ ] Perhitungan server sama dengan ringkasan di layar.
    - [ ] Produk menampilkan bahan, modal batch, harga jual, dan margin.
    - [ ] Perubahan resep produk aktif masuk antrean SuperAdmin.

## Risiko

- Katalog bahan tidak menyimpan harga, sehingga biaya resep harus diisi manual per produk.
- Perubahan harga bahan di katalog tidak otomatis mengubah resep lama karena nama bahan di-snapshot.
- Target produksi produk tidak sama dengan stok varian bila Admin mengubah stok manual setelahnya.
