# Rencana Minggu — Perbaikan Logic (6 Area)

> Status: **disetujui user** — urutan eksekusi A → B → C → D → E, migrasi mengiringi.
> Keputusan terkunci:
> - Pengajuan produksi **tetap Admin-only** (Owner read-only, didokumentasikan).
> - Hak selesaikan-refund **finansial Owner dicabut** (Owner hanya konfirmasi non-finansial).
> - Strategi password offline: auto-generate (tak terkait langsung, dicatat di Fase 2).

---

## A. Pencairan Dana (kritis)

**Masalah:** race condition di 4 titik, duplikasi entry-point, Setting diabaikan, bukti yatim, multi-store buta.

| # | Yang dibuat | Logic |
|---|---|---|
| A1 | Lock + cek-ulang dalam transaksi | `store` (dua controller): kunci baris wallet, hitung `available` di dalam transaksi. `setujui`: kunci `withdrawals` + cek status. `tandaiDibayar`: verifikasi-ulang status dengan lock (anti double-click/2 admin). `tolak`: transaksi + lock, hanya bila masih `pending` terkunci (anti overwrite `disetujui→ditolak` tanpa rollback). |
| A2 | Hapus duplikasi | `PencairanDanaController@store` vs `SaldoController@storePencairan` → satu action (route/notif disamakan). |
| A3 | Setting dipakai | `min`, limit harian (`maks_pengajuan_pencairan`), tampilkan saldo tertahan eksplisit; tambah aksi **batalkan** (release `tertahan` → `tersedia`). |
| A4 | Bukti anti-orphan | Simpan `file_bukti` setelah commit / hapus saat rollback (contoh benar: refund `selesaikan`). |
| A5 | Multi-store | `ownedStores()->first()` / `firstStoreId` → `store_id` dari request terotorisasi. |

## B. Pengembalian Dana (kritis)

**Masalah:** dua pintu finansial (Owner vs SuperAdmin race), approve tanpa uang, `escalated` buntu di SA, item mati, payment tak dipakai, omzet tak dikoreksi.

| # | Yang dibuat | Logic |
|---|---|---|
| B1 | Satu pintu finansial | Owner `selesaikan` → konfirmasi non-finansial saja; decrement wallet + bukti wajib hanya SuperAdmin; hapus route Owner duplikat; samakan validasi/notifikasi antar role. |
| B2 | SA putus `escalated` | `setujui/tolak` terima `requested` + `escalated`; `nominal_menunggu` include `escalated`; hapus set `selesai_pada` prematur saat approve. |
| B3 | RefundItem hidup | Partial wajib item + `jumlah == sum(nominal)`; `payment_id` nullable + wajib terverifikasi; cap kumulatif `SUM(selesai+aktif) <= payment/grand_total` dalam transaksi. |
| B4 | Order & omzet | Saat refund selesai: `Order → refund` + koreksi omzet/komisi; Owner scope multi-store + lock (anti approve ganda). |

## C. Order Tracking + Pengiriman (tinggi)

| # | Yang dibuat | Logic |
|---|---|---|
| C1 | Mutual exclusion | Blokir `confirm` bila ada refund aktif; blokir `storeRefund` bila sudah pernah refund selesai; sinkron **semua** shipment non-terminal saat confirm. |
| C2 | Guard pengiriman | Kurir/layanan harus aktif; resi unik; `kirim` dalam transaksi + lock + `canTransitionTo` + kurir wajib; parse estimasi numerik. |
| C3 | Mesin status order | `batalkan` batal bila ada shipment hidup; Owner `forward` hanya `dibayar`; SA `diterima→selesai` hanya dari `dikirim`; `gagal` bersihkan timestamp + sinkron order. |
| C4 | Notifikasi | Resi confirm/storeRefund/simpanResi ke customer; `kirim`/update SA ke owner. |

## D. Ajukan Produksi (tinggi, Admin-only)

| # | Yang dibuat | Logic |
|---|---|---|
| D1 | Transaksi + anti-duplikat | `store` dalam transaksi; tolak `requested/diproses` ganda untuk varian+order sama. |
| D2 | Warehouse valid | `target_warehouse_id` wajib se-toko + aktif; fallback gudang aktif (bukan acak). |
| D3 | Traceability | Kolom `production_orders.order_id` (nullable FK) + tampil di UI; `prioritas` masuk form; stop concat catatan. |
| D4 | Ringkasan Owner | Hitung full-store (bukan halaman aktif); petakan `requested → menunggu`; multi-store via `ownedStoreIds`. Dokumentasikan: pengajuan via Admin. |

## E. Koordinasi Gudang (tinggi)

| # | Yang dibuat | Logic |
|---|---|---|
| E1 | Guard kirim | Order harus `diproses`; from/to eksplisit se-toko + `different` + cek stok pra-buat. |
| E2 | Anti-duplikat | Satu order = satu transfer terbuka (butuh `stock_transfers.order_id`); tulis `status_ketersediaan`/catatan ke order. |
| E3 | Keterkaitan batal | `batalkan`/Edit order membatalkan transfer `requested` terkait. |
| E4 | Notifikasi terarah | Ke staf gudang terkait (bukan broadcast); perbaiki grup `orWhere` riwayat lintas toko. |

## F. Migrasi pendukung (nullable, aman untuk data lama)

1. `production_orders.order_id` → FK `orders.order_id` nullable.
2. `stock_transfers.order_id` → FK `orders.order_id` nullable.
3. `refunds.payment_id` → nullable (ubah kolom).

## Verifikasi per area

`php artisan migrate` → `php -l` → `php artisan view:cache` → render tinker halaman terkait → uji manual: double-submit (klik 2× cepat), approve 2 admin bersamaan, tolak-vs-setujui race, nominal mismatch, stok 0.

---

*Disusun: 12 September 2026 — dari audit read-only 3 agen + keputusan user.*
