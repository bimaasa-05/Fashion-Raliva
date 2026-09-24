# Penjelasan Revisi Batch D — Keuangan Lanjut, Promo, Laporan

Scope: file Owner (+ konstanta `WalletTransaction::JENIS_PENGELUARAN`).

---

## 1. Kategori + filter tabel keuangan

- Tabel mutasi: kolom **Kategori** baru + filter Kategori & Jenis (server-side,
  `withQueryString`, ada Reset).
- Tabel pengeluaran: filter Kategori (server-side).
- Kategori pemasukan (`Penjualan/Investor/Modal/Komisi/Lainnya`) tersimpan di
  `wallet_transactions.kategori` dan ikut tampil.

## 2. Chart 5 tahun

- Keuangan: selector **6 Bulan / 5 Tahun** (saldo akhir Desember per tahun).
- Laporan: period `1825` + tombol chart `5 Tahun`; bucket + export ikut
  agregasi tahunan. Judul chart mengikuti mode aktif.

## 3. Hapus card EBT

Kartu `Laba Sebelum Pajak` dihapus; grid `md:grid-cols-5` → `md:grid-cols-4`
(Omzet, Gross, EBITDA, Net). Key `ebt` dihitung tapi tak ditampilkan.

## 4. Minimal belanja Rp 3

`PromoController` Owner `store/update`: `minimal_pembelian` `min:0` → `min:3`
+ input `min=3` di form tambah. Nilai `0/null` lama tetap dibaca
`Tanpa minimum`.

## Batasan jujur

1. Filter tabel reset paginasi ke halaman 1 (bawaan query string).
2. Chart 5 tahun memakai tahun kalender penuh (Jan–Des), bukan 365 hari
   bergulir.
