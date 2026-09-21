# Integrasi Komplain ↔ Refund — Rencana Batch

> Dibuat: 2026-09-17
> Rencana penyambungan sistem **komplain** dan **refund** yang saat ini berdiri sendiri (ditemukan saat audit `alur-komplain-lengkap.md` dan `alur-refund-dan-saldo.md`).
> Status: **✅ SEMUA BATCH SELESAI 2026-09-18** (termasuk konsistensi `selesaikan` satu-jalur dan transisi `Order::STATUS_REFUND`).

---

## 1. Masalah yang Ingin Dipecahkan

1. Tidak ada `complaint_id` di tabel `refunds` → tidak bisa menjawab *"komplain mana yang berujung refund, dan untuk jumlah berapa"*.
2. Customer tidak bisa *mengubah komplain yang sudah ada* menjadi refund — ia harus keluar dari thread dan mengajukan refund terpisah (form modal order-tracking).
3. `Order::STATUS_REFUND = 'refund'` ada di konstanta & label tracking, tapi **tidak pernah dipakai sebagai transisi**, sehingga order yang sudah di-refund penuh tetap `selesai` dan tetap dihitung sebagai pendapatan (inkonsistensi dengan `alur-refund-dan-saldo.md` §6.2).
4. Aturan `selesaikan` tidak konsisten antar role: Owner = status saja, SuperAdmin = status + decrement wallet + bukti.

---

## 2. Keputusan yang Sudah Disetujui User (acuan desain)

| No | Keputusan |
|---|---|
| 1 | Refund dihitung di Rekap Karyawan **hanya saat status `selesai`** (dana benar-benar keluar). `disetujui` tidak dihitung. ✅ |
| 2 | Saat refund dieskalasi, `reviewed_by` **tetap admin penangan awal** — Owner tidak menimpa. ✅ |
| 3 | Tidak ada perubahan status komplain yang dipaksakan oleh refund: komplain dan refund tetap dua entitas yang bisa berdiri sendiri. |
| 4 | Semua dokumen plan di `Docs\plan` gaya kebab-case tanpa angka; file ini bagian dari suite komplain/refund. |
| 5 | `selesaikan` antar role = **satu jalur sama persis** via `RefundCompletionService` (user memilih, bukan opsi A "tanpa wallet"). ✅ |
| 6 | Transisi `Order::STATUS_REFUND` **dikerjakan sekarang** (user membalik rekomendasi "tunda"). ✅ |

---

## 3. Rencana Perubahan

### 3.1 Migrasi — kolom `complaints.complaint_id` di `refunds`

- Buat migration: `add_complaint_id_to_refunds_table`.
  - `refunds.complaint_id` → bigint nullable, index, FK → `complaints.complaint_id` (`ON DELETE SET NULL`).
  - Tanpa backfill data lama (refund legacy dikaitkan ke detil order, bukan komplain).
- Model `Refund`: tambah `belongsTo(Complaint::class, 'complaint_id')`.
- Model `Complaint`: tambah `hasOne(Refund::class, 'complaint_id')` (1 pesanan → 1 komplain aktif → (maks) 1 refund).

### 3.2 Customer — ajukan refund DARI dalam thread komplain

- `Customer\KomplainController` / view thread chat:
  - Tambah tombol "Ajukan Refund" (hanya bila order masih `dikirim`/`selesai` dan belum ada refund aktif — pakai guard yang sama dengan `storeRefund`).
  - Form modal berisi: `tipe_refund`, `jumlah` (≤ `grand_total`), `alasan` (min 20), `file_bukti_request` (gambar ≤4MB), `deskripsi_bukti_request`.
  - Submit → route `customer.refund.store` **ditambah input tersembunyi `complaint_id`** → disimpan ke `refunds.complaint_id`.
- Tampilan refund di thread: badge/link status refund terbaru komplain terkait.

### 3.3 Owner / Admin — jangan timpa `reviewed_by` di setujui/tolak eskalasi

- Ikuti perbaikan `refund-rekap-karyawan.md` §4.2 (guard `if (! $refund->reviewed_by)`).

### 3.4 Konsistensi `selesaikan` (✅ SELESAI — keputusan user: Satu Jalur Sama Persis)

> **Keputusan final (2026-09-18):** tidak memakai opsi A ("tanpa wallet"). Owner & SuperAdmin memanggil **service yang sama** `app/Services/RefundCompletionService::complete()`:
>
> - Potong `Wallet.saldo_tersedia` toko (`JENIS_REFUND_KELUAR`) + cek saldo cukup.
> - Wajib `file_bukti` (JPG/PNG/PDF ≤5MB) + `deskripsi_bukti` opsional.
> - **Kredit saldo akun customer** (`CustomerWalletService::refundToWallet`) bila order dibayar via `KODE_SALDO_AKUN`.
> - Rollback file bukti saat error; guard `lockForUpdate` anti-double-process.
> - Controller masing-masing tinggal memetakan error → flash (`Owner:error` / `SA:toast`) + notifikasi.

### 3.5 Status order `refund` (✅ SELESAI — transisi aktif)

- Saat refund **full** mencapai `selesai` **dan** `jumlah >= grand_total` **dan** order berstatus `dikirim`/`selesai`, order di-set `Order::STATUS_REFUND` (dalam transaction `selesaikan`, log `order.refunded`). Partial / jumlah kurang dari total → tidak flip.
- **Model keuangan** (anti double-deduct):
  - Laporan **dengan baris Refund** → pendapatan dibaca `whereIn([selesai, refund])` sehingga order refund tetap dihitung sebagai pendapatan kotor yang diimbangi baris Refund → **net 0**, konsisten lintas periode (`created_at` vs `diajukan_pada`) & all-time. Diterapkan di: `OwnerLaporanRingkasanSheet`, `OwnerLaporanPeriodeSheet`, `Owner/LaporanController`, `Admin/LaporanController`, `SuperAdmin/LaporanController::export`, `KaryawanReportService`.
  - Statistik **tanpa baris Refund** (dashboard SA/Owner, count filter) tetap `selesai` → order refund gugur alami (lebih akurat).
  - Sum Refund (expense) di semua laporan **tidak diubah** (partial & full tetap dihitung untuk mengimbangi pendapatan W1).

---

## 4. File yang Disentuh (ringkas)

| File | Perubahan |
|---|---|
| `database/migrations/*_add_complaint_id_to_refunds_table.php` (baru) | kolom + FK + index ✅ |
| `app/Models/Refund.php` | relasi `complaint()` ✅ |
| `app/Models/Complaint.php` | relasi `refund()` ✅ |
| `app/Http/Controllers/Customer/OrderTrackingController.php` | `storeRefund` simpan `complaint_id` (input opsional) ✅ |
| View thread komplain (customer) | tombol + modal "Ajukan Refund" + badge status refund ✅ |
| `app/Http/Controllers/Owner/PengembalianDanaController.php` | guard `reviewed_by` + `selesaikan` via service ✅ |
| `app/Services/RefundCompletionService.php` (baru) | satu jalur `selesaikan`: wallet + kredit saldo akun + flip `STATUS_REFUND` ✅ |
| `app/Http/Controllers/SuperAdmin/PengembalianDanaController.php` | `selesaikan` via service ✅ |
| `app/Http/Controllers/Admin/PengembalianDanaController.php` | scope `AdminContext` (index + 403) ✅ |
| `app/Services/KaryawanReportService.php` | `refundKaryawan` hanya `selesai`; pendapatan/pesanan `whereIn([selesai, refund])` ✅ |
| Export & controller laporan (Owner/Admin/SA) | pendapatan `whereIn([selesai, refund])` pada laporan ber-baris Refund ✅ |
| `Owner/PesananController` + view | filter & chip status `refund` ✅ |

---

## 5. Urutan Kerja yang Diusulkan

Semua sudah dieksekusi 2026-09-18 dalam satu batch:

1. Batch atribusi rekap karyawan (`refund-rekap-karyawan.md`) — rekap hanya `selesai`, `reviewed_by` tetap admin.
2. Scope `AdminContext` pada `Admin/PengembalianDanaController`.
3. `RefundCompletionService` → Owner & SA `selesaikan` satu jalur (wallet + kredit saldo akun).
4. Transisi `Order::STATUS_REFUND` (full refund) + penyesuaian R1 di laporan ber-baris Refund + filter `refund` di Owner pesanan.

## 6. QA Ideas

- Komplain dibuat → ajukan refund dari thread → `refunds.complaint_id` terisi. ✅
- Guard duplikat: ajukan refund kedua dari thread → ditolak UI (+ backend). ✅
- Refund `escalated` → Owner `setujui` → `reviewed_by` tetap admin. ✅
- Refund `selesai` tampil di Rekap Karyawan; `disetujui` tidak. ✅
- Owner `selesaikan` = potong wallet toko + kredit saldo akun customer + status `selesai` (bukan opsi A). ✅
- Order full-refund → status `refund`; partial → tetap `selesai`. ✅
- Laporan ber-baris Refund: pendapatan `whereIn([selesai, refund])` → net 0 dengan baris Refund. ✅
- Admin di luar toko → `403` saat mengakses/index refund lintas toko. ✅