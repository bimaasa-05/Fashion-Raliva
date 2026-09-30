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

## Revisi 2 — prioritas gudang utama + nama gudang sumber (2026-09-30)
- Kasus nyata: potongan 1 pcs tercatat di warehouse 4 (stok terbanyak),
  sedangkan layar Gudang menampilkan warehouse 5 (total 51) — membingungkan.
- `ShortfallStockAllocator` kini mengambil dari **gudang utama toko**
  (`warehouse_id` terkecil), fallback ke gudang berikutnya bila kurang.
- Nama gudang sumber tampil di mana-mana:
  - `StockMovement.alasan` → "… — Gudang Utama Bandung",
  - notif QC/Gudang/Admin + toast `siapkan`,
  - label view: "1 pcs dari Gudang Utama Bandung" (QC, Admin, modal detail),
  - `Order::shortfallMovements()` + `namaGudangShortfall()` (multi-gudang → "2 gudang").
- Perhatian Blade: `@if` inline harus diawali spasi (`gagal @if`),
  karena `gagal@if` tidak dikenali kompiler Blade (`\B@`).
- Test baru: `utama_warehouse_is_prioritized` + assertion nama gudang
  di alasan movement (`KekuranganSiapkanTest`).
