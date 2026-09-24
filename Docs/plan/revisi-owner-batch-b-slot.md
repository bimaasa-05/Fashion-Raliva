# Revisi Owner Batch B — Slot & Kuota

Keputusan: single-count quota; tanpa UI SuperAdmin baru.
Acuan analisa: `SlotService` (total = grants + subscription aktif),
double-count paket 2N, riwayat Kelola vs Paket terpisah.

## B1 — Single-count quota (satu sumber kebenaran)

- [ ] `SlotService::totalQuota()`: hanya dari `slot_grants`
      (hapus penjumlahan `store_slot_subscriptions`; subscription = metadata).
- [ ] Label seragam di semua halaman: `Sisa X dari Maksimal Y (Z terpakai, p%)`.
- [ ] Verifikasi angka: Kelola Slot, Paket Slot, moderasi, `canAdd`.

## B2 — Banner + Tambah Slot di Data Produk Owner

- [ ] `ProdukController@index`: pakai `SlotService` (ganti `SUM(slot_terpakai)`),
      kirim `$statusOptions` atau hapus dari blade.
- [ ] Blade: banner kuota + tombol `Tambah Slot → owner.kelola-slot`;
      link di pesan error `status()` saat kuota penuh.

## B3 — Menu Paket Slot terpisah

- [ ] Pecah sidebar: `Kelola Slot` dan `Paket Slot` tanpa `aliases`
      (pills internal tetap).

## B4 — Riwayat satu baris

- [ ] Timeline gabungan grant/request/subscription berlabel sumber
      (`ref_type`); tanpa `+N` hijau untuk pending/ditolak; paginasi
      (ganti `take(15)/limit 10`).

## B5 — Beli slot Admin + samakan alur

- [x] Admin ajukan pembelian slot (`Admin/SlotController`, menu Beli Slot).
- [ ] Samakan paket vs fleksibel (request-approve atau instan tercatat)
      — DITUNDA: butuh kolom paket di request + alur approve SA baru.
- [x] Cek `canAdd` di `Admin/DataProdukController@store`.

## Verifikasi

- [x] `php -l` + `view:cache` (tanpa migrasi baru).
- [x] Route `admin.slot` + `admin.slot.request` ADA.
- [ ] Manual: beli fleksibel → approve; beli paket; sisa/maksimal konsisten
      di semua halaman; riwayat tanpa duplikat; ajukan slot Admin.
