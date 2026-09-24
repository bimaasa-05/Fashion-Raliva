# Penjelasan Revisi Batch B — Slot & Kuota

Cakupan: `SlotService`, Data Produk Owner, Kelola/Paket Slot, Beli Slot Admin.
Prinsip: **satu sumber kebenaran kuota + label seragam di semua halaman.**

---

## 1. Single-count quota — kenapa `totalQuota` hanya dari `slot_grants`

Dulu: `totalQuota = SUM(slot_grants) + SUM(subscriptions aktif)`.
Setiap pembelian paket mencatat **keduanya** (subscription + grant
`ref_type=StoreSlotSubscription`), sehingga 1 paket N slot terhitung **2N**
di semua halaman (sisa/maksimal salah, `canAdd` keliru).

Sekarang: `totalQuota = SUM(slot_grants)` saja; subscription hanya metadata
(paket aktif, durasi). Angka historis paket lama tetap ganda di DB — yang
diperbaiki adalah cara hitung tampilannya.

Label seragam: `Sisa X dari Maksimal Y (Z terpakai, p%)` di Kelola Slot,
Paket Slot, banner Data Produk, dan halaman Beli Slot Admin.

## 2. Banner + Tambah Slot di Data Produk Owner

`ProdukController@index` dulu hitung dari `SUM(slot_terpakai)` (kolom yang
tidak pernah di-update → selalu 0). Sekarang pakai `SlotService`
(total/used/sisa/progress) + banner kuota + tombol **Tambah Slot**.
Kuota penuh → redirect ke kelola-slot (bukan teks polos).
`$statusOptions` yang undefined di blade kini dikirim dari controller.

## 3. Menu terpisah

Sidebar: `Kelola Slot` dan `Paket Slot` item sendiri (dulu satu item +
`aliases`). Pills internal tetap sebagai jalan pintas.

## 4. Riwayat gabungan satu baris

Timeline = grant (non-request) + permintaan fleksibel + langganan paket,
berlabel sumber (`Gratis Bawaan`, `Grant SuperAdmin`, `Pembelian`,
`Beli Fleksibel`, `Paket Berlangganan`), paginasi 15.
Baris `pending/ditolak` tampil tanpa `+` hijau agar tak dibaca sebagai
penambahan kuota.

Dua bug yang ketemu saat verifikasi (sudah diperbaiki):
- Kolom `store_slot_subscription_id` tidak ada — PK-nya `slot_subscription_id`.
- `index()` tidak menerima `$request` padahal paginasi memakainya.

## 5. Beli slot Admin + `canAdd`

Halaman + menu **Beli Slot** (kuota per toko + form pengajuan fleksibel
dengan pilih toko) → `SlotPurchaseRequest pending` → disetujui SuperAdmin
seperti alur Owner. `Admin/DataProdukController@store` menolak bila kuota
penuh dan mengarahkan ke Beli Slot.

## 6. Batasan jujur

1. Penyatuan alur paket vs fleksibel (paket bypass verifikasi) **ditunda** —
   butuh kolom paket di request + approve SA baru.
2. Grant paket lama tidak dihapus; angka tampil sudah benar via single-count.
3. `summaries()` masih N+1 query (boros untuk banyak toko, bukan error).
