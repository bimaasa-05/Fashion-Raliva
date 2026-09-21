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
  Refund: requested     (jumlah ≤ grand_total, tipe full/partial, dapat berisi complaint_id)
        │
        ▼
Admin toko (scope AdminContext — hanya toko yang ditugaskan)
  ├── setujui ──────► disetujui  (reviewed_by = Admin sedapatnya) ──────► selesai (Owner/SA)
  ├── tolak  ──────► ditolak     (reviewed_by = Admin sedapatnya, + alasan)
  └── eskalasi ─────► escalated  (reviewed_by = Admin) ──► Owner: setujui/tolak
                                                            (reviewed_by TETAP Admin — tidak ditimpa)
                                    ▲
SuperAdmin (observability) ─────────┘
  ├── setujui (requested → disetujui)
  ├── tolak  (requested → ditolak, alasan min 10)
  └── selesaikan (disetujui → selesai) — SATU JALUR via RefundCompletionService:
      pemotongan Wallet toko + kredit saldo akun customer + bukti + flip order full-refund
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

- `index()`: `pengajuan` = status `requested`+`escalated`; `riwayat` = sisanya (paginated). **Semua dibatasi** ke toko milik `AdminContext::assignedStoreIds()` (pola `whereHas('order', …)`).
- `setujui()`: dari `requested` atau `escalated` → `disetujui`, `reviewed_by = Auth::id()` (bila masih NULL).
- `tolak()`: → `ditolak`, `reviewed_by`, `alasan_penolakan` dari input, `selesai_pada=now()`.
- `eskalasi()`: hanya dari `requested` → `escalated`, `reviewed_by = Auth::id()`, notif ke Owner toko.
- Semua aksi **guard `assertBelongsToStore` (403)** di luar toko yang ditugaskan, menotifikasi customer (`TIPE_KOMPLAIN`) + self-notification.

### Tahap 3: Owner menangani eskalasi & penyelesaian

**Role:** Owner — `app/Http/Controllers/Owner/PengembalianDanaController.php` (web.php ±484-487 & 500-502)

- `index()`: **hanya** refund `escalated`/`disetujui` milik toko Owner (`OwnerContext::firstStoreId()`).
- `setujui()`/`tolak()`: hanya dari `escalated` (guard `assertStoreOwnerScope`).
  - **Keputusan user (final):** `reviewed_by` **tidak boleh ditimpa** dengan ID Owner; dibiarkan tetap admin yang menangani (guard `if (! $refund->reviewed_by)`).
- `selesaikan()`: dari `disetujui` → memanggil `RefundCompletionService::complete` — **satu jalur sama persis dengan SuperAdmin** (potong wallet toko + kredit saldo akun customer bila dibayar saldo akun + wajib `file_bukti`).

### Tahap 4: Penyelesaian dana (Service Bersama)

**Role:** SuperAdmin & Owner — via `app/Services/RefundCompletionService.php`

- `SuperAdmin/PengembalianDanaController::selesaikan()` (web.php ±244-247) dan `Owner::selesaikan()` keduanya menjadi pemanggil thin dari `RefundCompletionService::complete($refund, $path, $deskripsi)`.
- `complete()`:
  - Wajib status `disetujui` (guard `lockForUpdate` pada Refund & Wallet toko) → error "sudah berubah oleh pihak lain".
  - Cek saldo toko cukup → `wallet->decrement('saldo_tersedia', jumlah)`.
  - `WalletTransaction` `JENIS_REFUND_KELUAR` dengan `saldo_sebelum`/`saldo_sesudah`.
  - Update `status=selesai`, `file_bukti`, `deskripsi_bukti`, `bukti_diupload_pada`.
  - **Kredit saldo akun customer** bila payment `KODE_SALDO_AKUN`: `CustomerWalletService::refundToWallet` (mutasi `JENIS_REFUND_MASUK`; guard anti double-credit).
  - **Flip order**: full refund & `jumlah >= grand_total` & status order ∈ {`dikirim`,`selesai`} → `Order::STATUS_REFUND` (log `order.refunded`).
  - Pada error apa pun, file bukti dihapus dari storage (rollback) & exception dilempar; controller memetakan pesan error → flash/toast.
  - `ActivityLogger` `refund.complete` + notif customer & Owner. Perbedaan antar role hanya di styling flash & teks notifikasi.
- SuperAdmin juga punya `setujui()`/`tolak()` untuk refund `requested` (tolak wajib alasan min 10), dicatat `ActivityLogger` (`refund.approve`/`refund.reject`).

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
- Ada kolom `complaint_id` (nullable, FK) → komplain & refund terhubung ketika customer mengajukan refund dari thread komplain.
- Order::STATUS_REFUND = 'refund' AKTIF: order yang di-refund **penuh** (`tipe_refund=full`, `jumlah >= grand_total`) di-set `refund` saat `selesaikan`. Dampak dikoordinasikan di laporan (lihat §5.4).
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

1. ~~`wallet.saldo_tersedia` di-decrement hanya oleh SuperAdmin~~ **Tidak lagi:** Owner & SA memakai jalur sama (`RefundCompletionService`) — decrement wallet + kredit saldo akun customer + bukti. Owner kini memotong wallet tokonya sendiri.
2. ~~`Order::STATUS_REFUND` tidak terpakai~~ **Sudah dipakai:** full refund (`selesai`, `jumlah >= grand_total`) mengubah order ke `refund`. Laporan yang punya baris Refund menghitung pendapatan `whereIn([selesai, refund])` → net 0; statistik tanpa baris Refund tetap `selesai` (order refund gugur alami).
3. Refund legacy di DB `Raliva_Fashion` seluruhnya `reviewed_by = NULL` — tidak teratribusi ke karyawan mana pun (jangan di-backfill manual).
4. ~~Aksi Admin refund belum di-scope~~ **Sudah di-scope** `AdminContext::assignedStoreIds()` di `index` + semua aksi (guard 403 `assertBelongsToStore`).
5. Temuan minor (dibiarkan, dicatat): `selesai_pada` diisi saat `setujui`/`ditolak` oleh Admin/Owner padahal maknanya "dana keluar"; SA mengisinya hanya saat `selesaikan`. Tidak diubah agar tidak memutus navigasi UI.

---

## 7. Batasan & Urutan Nanti

- Batch atribusi rekap karyawan: `refund-rekap-karyawan.md` — ✅ selesai.
- Batch integrasi komplain↔refund + status order refund: `integrasi-komplain-refund.md` — ✅ selesai (termasuk `Order::STATUS_REFUND`).
- Batch saldo pelanggan: inti kredit otomatis (`CustomerWalletService`) sudah jalan; sisa = UI halaman "Saldo Saya", penarikan, dan pengelolaan batas saldo (menunggu persetujuan desain).