# Revisi Owner Batch D — Keuangan Lanjut, Promo, Laporan

Scope: file Owner (+ migrasi bila disebut).

## D1 — Kategori + filter tabel keuangan

- [ ] Kolom kategori di tabel mutasi (`Owner/keuangan/index`).
- [ ] Filter kategori/jenis di controller (`where kategori/jenis`) + UI
      `data-table-filter` (mutasi & expenses).

## D2 — Chart 5 tahun

- [ ] Agregasi tahunan 5 tahun terakhir + tombol range di chart
      keuangan (`SaldoController`) & laporan (`LaporanController`).

## D3 — Hapus card EBT

- [ ] Hapus div `Laba Sebelum Pajak` di `keuangan/index`,
      grid `md:grid-cols-5` → `md:grid-cols-4`; `$net = $ebit * 0.75`.

## D4 — Minimal belanja Rp 3

- [ ] `PromoController` Owner `store/update`: `minimal_pembelian` `min:0`
      → `min:3` + pesan error + placeholder view.

## Verifikasi

- [ ] `php -l` + `view:cache` + `migrate`.
- [ ] Manual: filter tabel, chart 5 tahun, 4 kartu, promo min 3 ditolak/sukses.
