# QC Auto-Shortfall Gudang — Penjelasan

Tanggal: 2026-09-30

## Masalah
Stok gudang tidak berkurang saat Produksi tekan Selesai maupun saat QC disimpan.
Kekurangan (mis. pesan 4, lulus 3) hanya tercatat sebagai angka `kekurangan_gudang`
dan menunggu Gudang menekan "Tandai Sudah Disiapkan" — itupun (sebelum revisi)
tidak benar-benar memotong stok.

## Alur baru
1. **Selesai Produksi** — tetap hanya laporan hasil (`jumlah_berhasil` + `jumlah_gagal`
   otomatis), tanpa menyentuh stok.
2. **QC + Packing** — `jumlah_lulus` tetap patokan final. Kekurangan
   (`total − lulus`) **langsung dipotong dari stok gudang** saat QC disimpan:
   - proporsional qty tiap varian (floor + sisa ke qty terbesar),
   - `lockForUpdate` + decrement + `StockMovement` "Penutup kekurangan",
   - `order_items.qty_dari_gudang` terisi per varian.
3. **Stok tidak cukup** — ambil semaksimal mungkin; sisa tetap tercatat di
   `kekurangan_gudang` dan masuk menu Kekurangan Gudang (fallback manual).
4. **Tombol "Tandai Sudah Disiapkan"** — tetap ada untuk sisa; memakai service
   yang sama dengan mode tolak-penuh.

## Arti kolom
- `jumlah_berhasil` = **barang siap kirim** (lulus produksi + dari gudang, cap total).
- `jumlah_gagal` = **cacat produksi** (`total − lulus`), bukan sisa kirim.
- Report produksi yang benar memakai baris `quality_checks`
  (`jumlah_lulus`/`jumlah_gagal`); `RiwayatProduksiController` dihitung dari
  `quality_checks.jumlah_lulus` agar pcs dari Gudang tidak menggelembungkan
  angka "unit berhasil" produksi.
- Deduksi final (`StockDeductionService`) melewati `qty_dari_gudang`
  → tidak double-deduct.

## Notifikasi
- Terpenuhi penuh → "Kekurangan Terpenuhi Otomatis dari Gudang" (Gudang info,
  Admin, Produksi).
- Sisa > 0 → "Kekurangan Produksi — Siapkan dari Gudang" (Gudang tugas)
  + info Admin/Produksi.

## File
- Baru: `app/Support/ShortfallStockAllocator.php`,
  `tests/Feature/QcAutoShortfallTest.php`, doc ini.
- Ubah: `PemeriksaanKualitasController@store`, `KekuranganController@siapkan`,
  `RiwayatProduksiController`, 4 view (QC/Admin/modal/Gudang),
  `ProduksiQcGudangTest`.

## Test
`QcAutoShortfallTest` 4/4 (penuh/kosong/separuh/idempoten),
`KekuranganSiapkanTest` 3/3, `ProduksiQcGudangTest` hijau.
