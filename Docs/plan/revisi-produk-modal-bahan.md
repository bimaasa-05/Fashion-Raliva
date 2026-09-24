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

- [ ] Buat migrasi `product_material_requirements` berisi:
  - [ ] `product_id`.
  - [ ] `material_id` opsional ke katalog bahan.
  - [ ] Snapshot `nama_bahan`.
  - [ ] `satuan`.
  - [ ] `jumlah_per_unit`.
  - [ ] `biaya_per_unit`.
- [ ] Tambahkan profil produksi pada produk:
  - [ ] `target_produksi`.
  - [ ] `modal_produksi`.
  - [ ] Biaya tambahan per unit bila diperlukan.
- [ ] Gunakan kembali daftar satuan `ProductionOrderBahan::SATUAN`.
- [ ] Hitung ulang modal di server, jangan hanya mengandalkan perhitungan JavaScript.

## Paket 2 — Rumus yang dipakai

Gunakan rumus berikut:

```text
modal_per_unit = jumlah seluruh(qty_per_unit x biaya_per_unit)
                 + biaya_tambahan_per_unit

modal_batch = modal_per_unit x target_produksi

margin_per_unit = harga_dasar - modal_per_unit

margin_persen = margin_per_unit / harga_dasar x 100%
```

- [ ] `target_produksi` minimal 1.
- [ ] Setiap bahan wajib memiliki nama, satuan, jumlah, dan biaya.
- [ ] Setiap produk wajib memiliki minimal satu bahan.
- [ ] Jaga pembulatan desimal untuk qty dan Rupiah.
- [ ] Tolak pembagi nol pada perhitungan margin persen.

## Paket 3 — Form tambah produk

- [ ] Tambahkan section “Rencana Produksi” pada modal tambah produk Admin.
- [ ] Sediakan input target produksi dan biaya tambahan.
- [ ] Sediakan baris bahan dinamis:
  - [ ] Pilihan bahan dari katalog.
  - [ ] Nama bahan manual.
  - [ ] Jumlah per unit.
  - [ ] Satuan.
  - [ ] Biaya per unit.
- [ ] Tampilkan ringkasan live:
  - [ ] Modal per unit.
  - [ ] Modal batch.
  - [ ] Harga jual.
  - [ ] Margin dan persentase.
- [ ] Simpan resep dan profil produksi dalam transaksi yang sama dengan produk.

## Paket 4 — Tampilan dan proposal perubahan

- [ ] Tampilkan tabel bahan dan ringkasan modal pada detail produk Admin.
- [ ] Sertakan resep dan profil produksi dalam snapshot “Ajukan Perubahan”.
- [ ] Tampilkan perbedaan resep lama dan baru pada review SuperAdmin.
- [ ] Jangan menghapus resep lama sebelum proposal disetujui.

## Verifikasi

- [ ] `php -l` untuk controller yang diubah.
- [ ] Migrasi naik dan rollback.
- [ ] `php artisan view:cache` lalu `view:clear`.
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
