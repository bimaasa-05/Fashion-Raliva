# Kekurangan Produksi — Manual oleh Gudang — Penjelasan

Tanggal: 2026-09-30 (revisi: otomatis → manual)

## Keputusan akhir
Kekurangan produksi **disiapkan manual oleh Gudang**, tidak otomatis.
Alur:
1. **Selesai Produksi** — laporan hasil (`jumlah_berhasil` + `jumlah_gagal`
   otomatis), tanpa menyentuh stok.
2. **QC + Packing** — `jumlah_lulus` patokan final; kekurangan
   (`total − lulus`) **hanya dicatat** di `kekurangan_gudang` + notif
   "Kekurangan Produksi — Siapkan dari Gudang". Stok tidak disentuh.
3. **Gudang → menu Kekurangan → "Tandai Sudah Disiapkan"** — di sinilah
   stok benar-benar dipotong (`ShortfallStockAllocator`, mode tolak-penuh):
   proporsional qty tiap varian, prioritas **gudang utama toko**,
   `lockForUpdate` + decrement + `StockMovement` "Penutup kekurangan … —
   {nama gudang}" + `qty_dari_gudang` per item + `jumlah_berhasil` naik.

## Arti kolom
- `jumlah_berhasil` = lulus produksi; naik ke full setelah Gudang menyiapkan.
- `jumlah_gagal` = cacat produksi (`total − lulus`).
- Nama gudang sumber tampil di movement/notif/toast/label
  (`Order::shortfallMovements()` + `namaGudangShortfall()`).
- Deduksi final (`StockDeductionService`) melewati `qty_dari_gudang`
  → tidak double-deduct.

## Riwayat perubahan
- Sempat dibuat otomatis saat QC disimpan, lalu dikembalikan ke manual
  atas permintaan Owner. `QcAutoShortfallTest` dihapus;
  `KekuranganSiapkanTest` (3/3) + `ProduksiQcGudangTest` mencakup alur manual.

## File terkait
- `app/Support/ShortfallStockAllocator.php` (dipakai `siapkan` saja),
- `Produksi/PemeriksaanKualitasController@store` (catat shortage),
- `Gudang/KekuranganController@siapkan` (potong stok manual),
- `app/Models/Order.php` (`shortfallMovements`, `namaGudangShortfall`),
- `Produksi/RiwayatProduksiController` (unit dari `quality_checks.jumlah_lulus`).

## Perhatian Blade
`@if` inline wajib diawali spasi (`gagal @if`), karena `gagal@if`
tidak dikenali kompiler Blade (`\B@`).
