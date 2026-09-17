# Alur Refund & Rancangan Saldo Pelanggan — Raliva Fashion

> Dibuat: 2026-09-17
> Dokumen ini menjelaskan **sistem pengembalian dana (refund)** yang sudah ada, relasi datanya, serta **proposal sistem saldo pelanggan** ala beautycare. Proposal hanya rancangan — tidak dieksekusi sekarang.
> Bacaan berpasangan: `alur-komplain-lengkap.md`, `integrasi-komplain-refund.md`, `refund-rekap-karyawan.md` (atribusi ke rekap karyawan).

---

## 1. Status yang Digunakan

`app/Models/Refund.php` (konstanta `STATUS_*`, `TIPE_*`):

| Status (DB) | Konstanta | Arti |
|---|---|---|
| `requested` | `STATUS_REQUESTED` | Diajukan customer, menunggu keputusan |
| `escalated` | `STATUS_ESKALASI` | Dieskalasi Admin ke Owner untuk keputusan final |
| `disetujui` | `STATUS_DISETUJUI` | Disetujui, menunggu penyelesaian dana |
| `ditolak` | `STATUS_DITOLAK` | Ditolak (+`alasan_penolakan`) |
| `selesai` | `STATUS_SELESAI` | Dana tuntas keluar (hanya status yang dihitung utk rekap) |

Tipe refund: `full` / `partial` (`tipe_refund`). Nomor tampilan: `RF-{tahun}-{5 digit}` (`getKodeAttribute`).

Order yang boleh di-refund: status `dikirim` atau `selesai` (`Customer\OrderTrackingController::storeRefund`).

---

## 2. Diagram Alur

```
Customer ajukan refund (order dikirim/selesai) — wajib foto bukti barang
  Refund: requested     (jumlah ≤ grand_total, tipe full/partial)
        │
        ▼
Admin toko
  ├── setujui ──────► disetujui  (reviewed_by = Admin) ──────► selesai (Owner/SA)
  ├── tolak  ──────► ditolak     (reviewed_by = Admin, + alasan)
  └── eskalasi ─────► escalated  (reviewed_by = Admin) ──► Owner: setujui/tolak
                                                            (reviewed_by TETAP Admin — keputusan user)
                                    ▲
SuperAdmin (observability) ─────────┘
  ├── setujui (requested → disetujui)
  ├── tolak  (requested → ditolak, alasan min 10)
  └── selesaikan (disetujui → selesai + wajib file_bukti + DECREMENT WALLET)
```

---

## 3. Detail Per Tahap

### Tahap 1: Customer mengajukan refund

**Role:** Customer — `app/Http/Controllers/Customer/OrderTrackingController.php → storeRefund()` (POST `customer.refund.store`, web.php:161)

1. Cek order milik user, status harus `dikirim` atau `selesai`.
2. Guard anti-duplikat: tidak boleh ada refund aktif (`requested`/`escalated`/`disetujui`) untuk order yang sama.
3. Validasi: `tipe_refund` (`full`/`partial`), `jumlah` (1 s.d. `order.grand_total`), `alasan` (min 20), `file_bukti_request` (gambar ≤4MB), `deskripsi_bukti_request` (opsional).
4. Bukti disimpan di `bukti-refund-request/{order_id}` (disk public).
5. `Refund` dibuat: `status=requested`, `requested_by=Auth`, `payment_id` dari `order.checkout.payment`, `diajukan_pada=now()`.
6. Notif `TIPE_ORDER` ke Owner toko: "Pengajuan Refund Baru".

### Tahap 2: Admin toko memutuskan

**Role:** Admin — `app/Http/Controllers/Admin/PengembalianDanaController.php` (web.php ±370-373)

- `index()`: `pengajuan` = status `requested`+`escalated`; `riwayat` = sisanya (paginated).
- `setujui()`: dari `requested` atau `escalated` → `disetujui`, `reviewed_by = Auth::id()`.
- `tolak()`: → `ditolak`, `reviewed_by`, `alasan_penolakan` dari input, `selesai_pada=now()`.
- `eskalasi()`: hanya dari `requested` → `escalated`, `reviewed_by = Auth::id()`, notif ke Owner toko.
- Semua aksi menotifikasi customer (`TIPE_KOMPLAIN`) + self-notification.
- ⚠️ **Temuan:** `index`/aksi Admin **tidak** memfilter `AdminContext::assignedStoreIds()` (beda dengan Komplain). Potensi akses lintas toko — dijadwalkan di `refund-rekap-karyawan.md` §7.

### Tahap 3: Owner menangani eskalasi

**Role:** Owner — `app/Http/Controllers/Owner/PengembalianDanaController.php` (web.php ±484-487 & 500-502)

- `index()`: **hanya** refund `escalated` milik toko Owner (`OwnerContext::firstStoreId()`).
- `setujui()`/`tolak()`: hanya dari `escalated` (guard `assertStoreOwnerScope`).
  - **Keputusan user (final):** `reviewed_by` **tidak boleh ditimpa** dengan ID Owner; harus tetap admin yang menangani (lihat `refund-rekap-karyawan.md` untuk perbaikan kodenya).
- `selesaikan()`: dari `disetujui` → `selesai` + `selesai_pada`. **Tanpa sentuhan wallet/saldo** (dana secara operasional ditangani di luar sistem).

### Tahap 4: SuperAdmin menuntaskan dana

**Role:** SuperAdmin — `app/Http/Controllers/SuperAdmin/PengembalianDanaController.php` (web.php ±244-247)

- `setujui()`/`tolak()`: dari `requested` (tolak wajib alasan min 10). Dicatat `ActivityLogger` (`refund.approve`/`refund.reject`).
- `selesaikan()`: dari `disetujui` → **wajib** `file_bukti` (JPG/PNG/PDF ≤5MB) + `deskripsi_bukti` opsional.
  - `DB::transaction` + `lockForUpdate` pada Refund & Wallet.
  - Cek saldo cukup → `wallet->decrement('saldo_tersedia', jumlah)`.
  - `WalletTransaction` dibuat dengan `jenis_transaksi = JENIS_REFUND_KELUAR`, `saldo_sebelum`/`saldo_sesudah`.
  - Update `status=selesai`, `file_bukti`, `deskripsi_bukti`, `bukti_diupload_pada`.
  - Pada error, file bukti dihapus dari storage (rollback).
  - Dicatat `ActivityLogger` (`refund.complete`) + notif customer & Owner.

---

## 4. Relasi Data

```
refunds ──belongsTo──▶ orders (order_id)
   │                       │
   ├──belongsTo──▶ payments (payment_id = order.checkout.payment)
   ├──belongsTo──▶ users (requested_by, reviewed_by)
   ├──hasMany────▶ refund_items (RefundItem: per item order)
   └──hasMany────▶ wallet_transactions (refund_id)   [SA selesaikan]

Catatan:
- TIDAK ada kolom complaint_id → komplain & refund belum terhubung.
- Order::STATUS_REFUND = 'refund' ADA di konstanta Order & label tracking,
  tetapi TIDAK pernah dipakai sebagai transisi — order yang di-refund penuh
  tetap berstatus `selesai` dan masih dihitung sebagai pendapatan.
```

---

## 5. Proposal: Sistem Saldo Pelanggan (ala beautycare)

> Status: **RANCANGAN — belum disetujui / belum dieksekusi.** Referensi pola: `app/Models/SaldoMutasi.php`, `PelangganSaldoController`, kasir beautycare.

### 5.1 Motivasi

Saat ini `selesaikan` milik SuperAdmin men-decrement **wallet toko** (`Wallet.saldo_tersedia`) tanpa menambah saldo pelanggan. Ke depan pelanggan perlu menerima dana ke saldo akun miliknya, ala saldo kasir/pelanggan di beautycare.

### 5.2 Sketsa (analogi beautycare → Raliva)

| Konsep beautycare | Usulan di Raliva-Fashion |
|---|---|
| `saldo_pelanggan` | relasi/hasMany pada `User` (mis. `CustomerSaldo`) |
| `SaldoMutasi` | entitas mutasi: `id_user`, `jenis` (refund masuk / penarikan keluar), `jumlah`, `saldo_sebelum`, `saldo_sesudah`, `referensi` (refund_id) |
| Kontroler saldo | `Customer/..` halaman "Saldo Saya" (mutasi + penarikan) |
| Sumber dana | `selesaikan` refund → kredit saldo pelanggan + debit wallet toko + `WalletTransaction` (transaksi gemini) |

### 5.3 Keputusan yang Sudah Digariskan

1. **Refund dihitung untuk rekap karyawan hanya saat status `selesai`** (dana benar-benar keluar) — konsisten dengan store-level. `disetujui` belum.
2. Atribusi eskalasi **tetap pada admin** penangan awal (Owner tidak menimpa `reviewed_by`).
3. Implementasi saldo pelanggan = pekerjaan terpisah utk dicek dengan user sebelum dimulai (skema tabel DB, UI pelanggan, batasan penarikan, kapan kredit terjadi).

---

## 6. Temuan Jujur (kondisi saat ini)

1. `wallet.saldo_tersedia` di-decrement **hanya oleh SuperAdmin** saat `selesaikan`; alurnya tidak dibuka untuk Owner (Owner hanya menandai status).
2. `Order::STATUS_REFUND` tidak terpakai → laporan "pendapatan" masih menganggap order yang sudah di-refund penuh sebagai penjualan. Perlu keputusan kapan order di-set `refund` (lihat `integrasi-komplain-refund.md`).
3. Refund legacy di DB `Raliva_Fashion` seluruhnya `reviewed_by = NULL` — tidak teratribusi ke karyawan mana pun (jangan di-backfill manual).
4. Aksi Admin refund belum di-scope `AdminContext` (bandingkan: komplain admin sudah) — dijadwalkan perbaikan.

---

## 7. Batasan & Urutan Nanti

- Batch attibusi rekap karyawan: `refund-rekap-karyawan.md`.
- Batch integrasi komplain↔refund + status order refund: `integrasi-komplain-refund.md`.
- Batch saldo pelanggan: menunggu persetujuan desain (baru sketsa di §5).