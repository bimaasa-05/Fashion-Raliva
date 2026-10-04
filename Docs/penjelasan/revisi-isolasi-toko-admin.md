# Penjelasan Revisi: Isolasi Data Antar Toko (Admin)

Tanggal: 2026-10-01.

Masalah yang dilaporkan: **data hasil seeder di satu toko bisa muncul di
admin toko lain**. Seeder memang hanya membuat satu toko ("Raliva Atelier
Jakarta"), tetapi user yang sudah punya toko kedua + admin kedua melihat data
toko pertama bocor ke halaman admin kedua.

Prinsip yang ditegakkan: **Admin Toko hanya melihat data toko yang
ditugaskan padanya** (`store_staff` via `AdminContext::assignedStoreIds()`).

---

## 1. Akar masalah (hasil audit semua controller Admin)

| # | Lokasi | Bocornya apa |
|---|---|---|
| 1 | `Admin/DataProdukController.php` `index()` | Daftar produk (`Product::paginate`) + 4 stat (`count`) + `pendingUpdateIds` **tanpa `where store_id`** — semua produk semua toko tampil di semua admin. Ini penyebab utama. |
| 2 | `Admin/DataPesananController.php` `index()` | Dropdown customer form pesanan: 50 customer terbaru **tanpa filter toko**. |
| 3 | `Admin/DataCustomerController.php` `index()` | `withCount` total pesanan + `withSum` omzet + drawer 5 order terakhir **lintas toko**; plus `orWhereDoesntHave('orders')` menampilkan semua customer tanpa order ke semua admin. |
| 4 | `App\Support\AdminContext.php` `fallbackAdmin()` | Fail-open: bila user bukan role Admin (atau guest), otomatis memakai **admin pertama** (`orderBy user_id` = admin seeder toko 1) sehingga query yang sudah benar pun menampilkan data toko 1 ke orang yang salah. |

---

## 2. Perbaikan

1. **DataProduk `index()`** — `$scopeIds = AdminContext::assignedStoreIds()`;
   query produk, keempat stat, dan `pendingUpdateIds`
   (`ProductUpdateRequest` punya kolom `store_id`) semua di-`whereIn`.
2. **DataPesanan `index()`** — dropdown customer hanya customer yang punya
   minimal 1 order di toko admin (`whereHas('orders', whereIn store)`).
   Alur customer baru tidak rusak: order **offline** tetap auto-create
   customer (`DataPesananController.php:230-246`); order online pertama
   customer baru dibuat lewat storefront.
3. **DataCustomer `index()`** — `withCount`/`withSum`/drawer order/drawer
   review semuanya di-constraint ke toko admin (nama atribut view tidak
   berubah: `total_pesanan`, `orders_sum_grand_total`); `orWhereDoesntHave`
   dihapus — customer tanpa order tidak tampil sampai punya order pertama.
4. **`fallbackAdmin()` jadi fail-closed** (`return null` + komentar).
   Tidak ada pemanggil di luar `AdminContext` sendiri, jadi aman:
   user yang salah kini mendapat daftar toko kosong, bukan data admin lain.

---

## 3. Yang TIDAK diubah (global by design, bukan bocor)

- `suppliers`, `categories`, `payment_methods`, `platform_bank_accounts`,
  `product_slot_packages` — tidak punya kolom `store_id` (master platform).
- `notifications`, `activity_logs` — hanya punya `user_id`
  (user-scoped via `forUser(Auth::id())`); komposer sidebar notifikasi
  sudah benar per-user.
- `couriers` hybrid (`store_id` nullable = global + milik toko) — query
  Admin sudah menangani keduanya.
- Pencocokan customer order offline by email/telepon tetap global
  (identifikasi walk-in; order-nya tetap tercatat di toko admin).

---

## 4. Bukti uji

- Baru: `tests/Feature/AdminStoreIsolationTest` — **3 passed, 13 assertions**:
  produk toko lain tidak tampil (milik sendiri tampil); dropdown customer
  pesanan terisolasi; halaman customer terisolasi.
- Regresi area sentuh (produk, pesanan, customer, slot):
  **73 passed + 2 skipped** (skip bawaan, bukan dari perubahan ini),
  343 assertions — termasuk `SlotHabisTest`, `QcVisibilityTest`,
  `CustomerLoginConsistencyTest`, dan suite produk.
- `php -l` semua file yang diubah bersih.

Catatan saat verifikasi (bukan bug aplikasi): test sempat merah karena
`$produk->replicate()` menyalin `deskripsi` yang mengandung nama produk toko
1 — diperbaiki dengan menimpa `deskripsi` replika di test.

---

## 5. Batasan jujur

1. Customer tanpa order tidak terlihat di halaman Data Customer maupun
   dropdown pesanan online sampai punya order pertama (lihat §2 poin 2–3).
2. Bila bisnis ingin `Supplier` terisolasi per toko, perlu kolom `store_id`
   + migrasi baru (di luar revisi ini).
3. Isolasi `RiwayatAktivitas` berbasis aktor (`user_id`), bukan target
   store — satu tim toko yang sama tetap saling melihat aksinya.
