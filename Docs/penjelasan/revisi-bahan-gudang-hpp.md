# Penjelasan: Bahan Pindah ke Gudang + HPP Manual

Untuk: Admin, Gudang, Produksi, Owner (nonteknis).

## Ringkasan

Alur produk disederhanakan sesuai simulasi yang disepakati:

1. **Admin** isi form simple: foto + info + **HPP/Modal** (baru, sebelum Harga Jual) + variasi & stok. Tanpa target/resep/operasional.
2. Begitu Admin submit (menunggu SuperAdmin), **Gudang dapat notifikasi** "Produk Baru Perlu Bahan" → buka menu **Bahan Produk** → input bahan (nama + jumlah + satuan).
3. Saat ada pesanan dan Produksi **accept**, bahan otomatis tersalin ke pesanan → **tampil di Produksi**. Kurang bahan? Produksi tambah sendiri per pesanan (fitur existing).

## Detail per peran

**Admin.** HPP diketik manual (mis. 65.000) sebelum Harga Jual (mis. 150.000). Ringkasan produk menampilkan HPP + margin otomatis (= harga − HPP). Target produksi & biaya operasional dihapus dari alur Admin. Edit produk tetap ada (nama, HPP, harga, foto, varian) via persetujuan SuperAdmin — seksi resep/target/operasional hilang dari Edit maupun halaman persetujuan.

**Gudang.** Menu baru **Bahan Produk**: produk tanpa bahan tampil paling atas ("Belum ada"). Klik Input/Ubah Bahan → tambah baris (nama, jumlah/unit, satuan) → Simpan (mengganti daftar). Scope per toko.

**Produksi.** Tidak ada tombol baru: daftar bahan produk tampil otomatis sebagai "Bahan dari Admin" begitu accept; tombol tambah bahan per pesanan tetap seperti semula.

## Sisi teknis (Admin IT)

- `store()`: validasi master + `hpp` → `modal_produksi`; 1 transaksi (produk + foto + varian + stok). `storeRencana()` + route + modal rencana + IIFE `rp-*` **dihapus**.
- `update()`: tanpa target/resep/operasional; tambah `hpp` → `modal_produksi` via approval. `ProductUpdateApplier`: terapkan HPP, jangan sentuh bahan/operasional. `PerubahanProdukController::compare()`: baris HPP + Margin (tanpa `compareRecipes`); view diff resep/operasional dihapus.
- `Gudang\BahanProdukController` (index/store, replace idempoten, scope toko) + route `gudang.bahan-produk(.store)` + menu sidebar + notifikasi produk-baru ke Gudang se-toko dari `store()`.
- `accept()`: salin resep tiap item → `production_order_bahan` (`sumber=admin`, jumlah = per-unit × qty, idempoten via cek existing).
- Test: `ProductRecipeTest` (HPP), `GudangBahanTest` 7 test, `ProductUpdateWorkflowTest` ditulis ulang (HPP + bahan utuh), `FormPolish`/`ColorValidation` disesuaikan.

## Verifikasi

- Regresi 67 passed + 2 skipped (skip = DB lokal tinggal 1 toko: uji scope toko-kedua; bukan bug).
- `php -l`, `view:cache/clear`, `git diff --check` bersih.
- Catatan: payload tanpa `hpp` kini 302 (wajib); ganti user antar-request wajib `flushSession`.

## Belum dikerjakan

- Verifikasi browser manual 3 role + mobile.
- Bila nanti butuh biaya bahan: tambah `biaya_per_unit` di form Gudang (kolom DB sudah ada) + aktifkan koreksi modal otomatis.
