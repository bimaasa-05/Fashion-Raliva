# Revisi — Fulfillment, Stok, Keuangan, Slot, Bahan

Tanggal keputusan: 2026-09-25.
Status dokumen: disepakati, dieksekusi bertahap.

## Keputusan yang dikunci

- Pisah penuh: `tipe_pesanan` = status customer (online = user terdaftar, offline = tamu); kolom baru `orders.metode_fulfillment` (`ambil`/`diantar`) untuk cara terima barang, bisa dikombinasi 2×2.
- Stok minimum: input dihapus (tambah + edit), sistem tulis 10 otomatis.
- Pemasukan Investor/Modal → omzet-only (tanpa increment saldo_tersedia); Penjualan/Komisi/Lainnya tetap ke saldo. Fix: pengeluaran Admin ikut decrement + jurnal. Form pemasukan Owner disamakan pengeluaran.
- Slot habis → dialog 2 pilihan (ajukan satuan / beli paket); paket dibuka untuk Admin.
- +Bahan: investigasi klik + pastikan bahan Gudang tampil.

## Paket 1 — Pisah fulfillment

- [x] Migrasi: `orders.metode_fulfillment` (`ambil`/`diantar`), backfill online→diantar, offline→ambil; model const + fillable + helper `isDiantar()`.
- [x] Tambah Pesanan: radio customer + radio fulfillment independen; label baru.
- [x] `alihFulfillment` → alih fulfillment; aturan ongkir void tetap.
- [x] Pengiriman: dua tombol per order; tab Diantar/Diambil; tracking + invoice + auto-complete ikut field baru.
- [x] Test kombinasi 2×2 + alih + tracking.

## Paket 2 — Stok minimum 10

- [x] Hapus input (tambah + edit + hidden sync); tulis 10; validasi min dihapus.
- [x] Test: produk baru min 10; status tetap jalan.

## Paket 3 — Keuangan

- [x] Pemasukan Investor/Modal omzet-only (Owner + Admin); pengeluaran Admin decrement + jurnal; form Owner seragam.
- [x] Test terkait + pencairan.

## Paket 4 — Slot

- [x] Paket untuk Admin; dialog slot habis 2 pilihan.
- [x] Test terkait.

## Paket 5 — Bahan

- [x] Investigasi + perbaiki klik; pastikan bahan Gudang tampil; regresi.

## Paket 6 — Verifikasi

- [x] `php -l`, `view:cache/clear`, `git diff --check`, regresi, penjelasan `Docs/penjelasan/revisi-fulfillment-keuangan-slot-bahan.md`, manual.
