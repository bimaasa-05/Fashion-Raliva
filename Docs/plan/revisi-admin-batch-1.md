# Revisi Admin Batch 1 — Paket A–F

Dokumen perencanaan sebelum eksekusi. Scope dikunci: **hanya file Admin + Owner**,
kecuali pengecualian minimal yang dicatat di bawah.

## Keputusan yang disepakati

- #21 Chat tag: **DICORET** (menyentuh Customer & SuperAdmin).
- Popup refund customer (item-picker, ajukan-ulang): **DITUNDA** (file Customer).
- Ambil-di-toko: online + offline, semua status.
- Ukuran per kategori: tabel + seeder, **tanpa UI SuperAdmin**.
- Kurir per toko: pivot ringan `store_courier_settings`.
- Supplier: `jumlah` per-bahan (migrasi), lepas `stok` + `jenis` dari UI.

## Pengecualian minimal di luar scope

1. `Gudang/PemindahanStokController`: 4 referensi `route('admin.koordinasi-gudang')`
   harus diganti ke `admin.pesanan` (kalau tidak → `RouteNotFoundException`).
   Tanpa ubah logic Gudang lain.
2. Approval `owner.gudang.setujui/tolak` dialihkan (file Owner, dalam scope).

## Paket A — Pesanan (Admin)

- [ ] `DataPesananController@proses`: hapus validasi `bahan.*` + `create ProductionOrderBahan`
      + guard `bahanList()->exists()`; validasi tinggal `tgl_mulai/berakhir`;
      ubah log/notif Produksi ("menunggu input bahan Produksi").
- [ ] View `Admin/pesanan/index`: hapus modal proses form bahan
      (`bahan-container`, `addBahanRow/onBahanInput`, `bahanMasterJson`);
      modal proses jadi konfirmasi + jadwal saja.
- [ ] Badge status beda warna: `diproses` amber, `dikirim` biru, `selesai` hijau
      (sinkron pill `Owner/pesanan`).
- [ ] Modal detail: tambah tanggal pesanan (`created_at`), jadwal produksi,
      `diambil_pada`, `catatan`.
- [ ] Kolom/aksi WhatsApp hijau (`wa.me`, `0→62`, pola `Admin/customer/index`)
      di semua status; tambah `nomor_telepon` di eager `checkout.user`.
- [ ] Modal tambah pesanan: textarea `catatan` → validasi → `orders.catatan`
      → tampil di detail + pengiriman.

## Paket B — Pengiriman (Admin)

- [ ] `selesai()`: longgarkan ke offline `dibayar→dikirim` + online ambil-langsung;
      tombol `Selesai (Diambil)` di tabel pesanan & pengiriman + konfirmasi.
- [ ] `$siapDiambil`: `whereIn` status boleh + sertakan online.
- [ ] Edit Resi: tombol/modal di tabel riwayat (reuse upsert `simpanResi`,
      guard `pending/diproses` + order belum dikirim).
- [ ] `simpanResi`: tambah `Notification::create` ke customer (kurir+resi+estimasi).

## Paket C — Invoice + Verifikasi + Kalender (Admin, + tombol Owner)

- [ ] Invoice: route `GET admin.pesanan/{pesanan}/invoice` + method + view
      print-friendly (kop, item snapshot, total, payment, resi, tanda tangan);
      tombol di kolom Aksi + detail; ganti `window.print()` Owner.
- [ ] Verifikasi: helper icon per metode (QRIS/bank/e-wallet/tunai) + badge
      status berwarna + info deadline/verifier di modal + samakan icon tolak.
- [ ] Kalender: class seragam + `min/max` semua `type=date/datetime-local`;
      tambah input `estimasi_tiba` di form resi; filter `dari/sampai` laporan;
      validasi `berakhir_pada ≥ mulai_pada` di `PromoController`.

## Paket D — Produk & Supplier (Admin)

- [ ] Ukuran per kategori: migrasi `store_category_sizes` + seeder default
      (Fashion `XS–XXL`, Sepatu `38–44`, dll) + lookup `Store.kategori → sizes`
      di `DataProdukController@index` (fallback hardcode lama).
- [ ] Supplier: migrasi `jumlah` di `supplier_bahan` (+ fillable/validasi);
      hapus input `stok` + radio `jenis` dari modal (kolom DB dibiarkan);
      kolom Jumlah per-bahan di modal + tabel.

## Paket E — Pencabutan Logistik (Admin)

- [ ] Hapus: Ajukan Produksi (`admin.permintaan-produksi*`), Koordinasi Gudang
      (`admin.koordinasi-gudang*`), Bahan Produk (`admin.bahan-produksi*`):
      menu sidebar + routes + controllers + views.
- [ ] Alihkan approval `owner.gudang.setujui/tolak`; perbaiki 4 referensi
      Gudang (pengecualian 1).
- [ ] Verifikasi akhir: grep `admin.koordinasi-gudang|permintaan-produksi|admin.bahan-produksi` = 0.

## Paket F — Transaksi, Kurir, Refund, Laporan (Admin + Owner)

- [ ] Kategori pemasukan: migrasi `kategori` di `wallet_transactions` + select
      di form Admin & Owner.
- [ ] Pengeluaran: select + datalist histori kategori (pola Owner).
- [ ] Kurir pivot: migrasi `store_courier_settings` + CRUD `admin.kurir`
      + filter dropdown pengiriman + validasi scope di `simpanResi`.
- [ ] Refund Admin: kolom Status Pesanan + `grand_total` + metode bayar,
      `nomor_order` (ganti `order_id`), pisah `disetujui_pada/selesai_pada`,
      perbaiki copy eskalasi.
- [ ] Laporan Admin: helper `ralivaShortRp`, filter tanggal, perbaiki hitung
      refund, export ala Owner; **hapus section Rincian per Toko + query `perToko`**.

## Urutan eksekusi

A → B → C → D → E → F. Verifikasi manual per paket sebelum lanjut.

## Status eksekusi (2026-09-22)

- [x] Paket A, B, C, D, E, F selesai.
- Ditunda: export Excel/PDF ala Owner di laporan Admin; UI kelola ukuran
  di SuperAdmin (seeder + fallback code saja); popup refund customer.
- Kolom DB `suppliers.stok/jenis` dibiarkan (tak dipakai UI lagi).
- `ProductionOrderBahan.sumber=admin` kini hanya historis (semua input baru
  dari Produksi).
