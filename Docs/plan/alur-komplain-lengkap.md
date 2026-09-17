# Alur Komplain Lengkap — Raliva Fashion

> Dibuat: 2026-09-17
> Dokumen ini menjelaskan **sistem komplain** Raliva dari sisi Customer sampai penutupan oleh SuperAdmin, berdasarkan implementasi yang sudah tersambung ke database.
> Bacaan berpasangan: `alur-refund-dan-saldo.md` (sistem pengembalian dana) dan `integrasi-komplain-refund.md` (rencana penyambungan keduanya).

---

## 1. Status yang Digunakan

`app/Models/Complaint.php` (konstanta `STATUS_*`):

| Status (DB) | Konstanta | Arti | Siapa yang bisa set |
|---|---|---|---|
| `open` | `STATUS_OPEN` | Komplain baru menunggu balasan | Customer (buat), siapa pun yang balas |
| `diproses` | `STATUS_DIPROSES` | Toko/Admin sudah membalas | Admin/Owner saat balas |
| `escalated` | `STATUS_ESKALASI` | Dieskalasi ke Owner untuk keputusan final | Admin eskalasi, SuperAdmin eskalasi |
| `selesai` | `STATUS_SELESAI` | Ditutup oleh platform | **hanya SuperAdmin** (`tutup`) |
| `ditutup` | `STATUS_DITUTUP` | Status cadangan (belum dipakai di flow saat ini) | — |

Kategori (`kategori`): `produk`, `pengiriman`, `pelayanan`, `lainnya`.

Order yang boleh dikomplain: status `dikirim` atau `selesai` (lihat `Customer\KomplainController::create` → `whereIn('orders.status', [STATUS_DIKIRIM, STATUS_SELESAI])`).

---

## 2. Diagram Alur

```
Customer ajukan komplain (order dikirim/selesai)
  Complaint: open     (+ ComplaintMessage pertama, notif ke Owner toko)
        │
        ▼
Admin Toko balas ──► Complaint: diproses   (notif ke customer)
        │
        ├── Admin: Eskalasi ──► Complaint: escalated ──► Owner toko balas
        │                                                    │
        └── Customer balas lagi ──► Complaint: open (lagi) ◀──┘
                                                               │
SuperAdmin: Tutup (dari open/diproses/escalated)               │
  Complaint: selesai + diselesaikan_pada                       │
  + pesan sistem bila ada catatan ─────────────────────────────┘
SuperAdmin juga bisa Eskalasi (open/diproses → escalated) + balas
```

**Catatan alur:** balasan Customer mengembalikan status ke `open` bila bukan `open`/`escalated` saat itu (`Customer\KomplainController::storeMessage` baris 248-250).

---

## 3. Detail Per Tahap

### Tahap 1: Customer mengajukan komplain

**Role:** Customer — `app/Http/Controllers/Customer/KomplainController.php`

- `create()`: menampilkan form + daftar order milik customer yang berstatus `dikirim`/`selesai` (`eligibleOrders`), opsional pre-select order via `?order=`.
- `store()` (POST `customer.komplain.store`):
  1. Validasi `order_id` (harus milik user), `kategori` (enum), `subjek` (≤150), `deskripsi` (20–2000).
  2. Dalam satu `DB::transaction`: buat `Complaint` (status `open`, `dibuat_pada = now()`) + `ComplaintMessage` pertama (sender = customer, isi = deskripsi).
  3. Notifikasi `TIPE_KOMPLAIN` ke owner toko: "Komplain Baru".
- Rute customer: `customer.komplain` (`index`), `customer.komplain.create`, `customer.komplain.store`, `customer.komplain.messages` (GET/POST), `customer.komplain.messages.update`, `customer.komplain.messages.destroy` (web.php ±161-169).

### Tahap 2: Admin toko membalas

**Role:** Admin — `app/Http/Controllers/Admin/KomplainController.php`

- `index()`: daftar komplain **di-scope toko** via `App\Support\AdminContext::assignedStoreIds()` (`whereHas('order', store_id in ...)`). Statistik: `terbuka`, `menunggu`, `selesai`, `selesaiBulanIni`, `resolution`.
- `storeMessage()` (AJAX): tulis `ComplaintMessage`; if status `open` → update status `diproses`. Notif ke customer. **Tidak membuka kunci** untuk komplain `selesai`/`ditutup` (422).
- `messages()`, `updateMessage()` (edit ≤15 menit, hanya pesan sendiri), `destroyMessage()` (hapus `per=me` atau `per=all` ≤2 hari untuk pesan sendiri).
- `eskalasi()`: status `open`/`diproses`/`escalated` yang belum `selesai`/`ditutup` → `escalated`, notif ke Owner toko (fallback Owner pertama bila toko tak punya owner).
- Guard scope: `belongsToStore()` + `abort(403)` bila komplain di luar toko yang ditugaskan.
- Rute admin: `admin.komplain...` (web.php ±374-379).

### Tahap 3: Owner menangani eskalasi

**Role:** Owner — `app/Http/Controllers/Owner/KomplainController.php`

- `index()`: **hanya** komplain `where('store_id', OwnerContext::firstStoreId())` **dan** status `escalated`, + `eskalasiCount`.
- `storeMessage()` (AJAX): balas dalam thread — tapi hanya boleh pada komplain `escalated` (guard `belongsToStoreEscalated`, selain itu 404). Bila status `open` → `diproses` (jarang terjadi di scope ini).
- Owner **tidak punya tombol tutup**. Keputusan "selesai" tetap di SuperAdmin.
- Rute owner: `owner.komplain...` (web.php ±476-480).

### Tahap 4: SuperAdmin menutup / mengeskalasi (observability + moderasi)

**Role:** SuperAdmin — `app/Http/Controllers/SuperAdmin/KomplainController.php`

- `index()`: daftar **semua** komplain lintas toko + statistik per status.
- `storeMessage()`: balasan "Balasan Platform" — dikirim ke customer + owner toko.
- `eskalasi()`: hanya dari `open`/`diproses` → `escalated`, otomatis menulis pesan sistem *"Komplain ini dieskalasikan ke Owner toko..."*, notif ke Owner, dicatat `ActivityLogger` (`complaint.escalate`).
- `tutup()`: **satu-satunya aksi penutupan** dalam sistem. Berlaku dari `open`/`diproses`/`escalated` (termasuk alias `'baru'`) → `selesai` + `diselesaikan_pada = now()`. Catatan opsional ditulis sebagai pesan `[Ditutup oleh platform] ...`. Dicatat `ActivityLogger` (`complaint.close`).
- Rute SA: `superadmin.komplain...`, `superadmin.komplain.eskalasi`, `superadmin.komplain.tutup` (web.php ±305-311).

---

## 4. Role & Aksi (ringkasan)

| Role | Aksi | Efek status | Catatan |
|---|---|---|---|
| Customer | Buat komplain | `open` | Hanya order `dikirim`/`selesai` |
| Customer | Balas | `open` (jika bukan escalated) | Edit ≤15 mnt, hapus ≤2 hari |
| Admin | Balas | `diproses` (dari `open`) | Scope toko via AdminContext |
| Admin | Eskalasi | `escalated` | Notif ke Owner |
| Owner | Balas | tetap `escalated` | Hanya komplain escalated toko-nya |
| SuperAdmin | Balas | — | Boleh di status apa pun |
| SuperAdmin | Eskalasi | `escalated` | Pesan sistem + ActivityLogger |
| SuperAdmin | Tutup | `selesai` | **Eksklusif SA** + `diselesaikan_pada` |

---

## 5. Temuan Jujur (kondisi saat ini)

1. **Penutupan eksklusif SuperAdmin.** Admin dan Owner tidak bisa menandai komplain selesai. Status `selesai` hanya dicapai lewat `tutup()` milik SA. Bila ingin desentralisasi (mis. Owner menutup setelah eskalasi), perlu aksi baru — lihat `integrasi-komplain-refund.md`.
2. **Dua mekanisme eskalasi berbeda.** Eskalasi Admin (`Admin\KomplainController::eskalasi`) hanya mengubah status + notif; eskalasi SA (`SuperAdmin\KomplainController::eskalasi`) juga menulis pesan sistem dan ActivityLogger. Konsistensi bisa disatukan.
3. **Komplain dan refund benar-benar terpisah.** Tidak ada kolom `complaint_id` di tabel `refunds` dan tidak ada tombol ajukan refund dari dalam thread komplain. Dampak analitik: tidak bisa tahu komplain mana yang berujung refund.
4. **Status cadangan `ditutup`** ada di model & badge UI namun tidak pernah ditetapkan oleh flow saat ini (penutupan memakai `selesai`).
5. Notifikasi mengarah ke berbagai `url` (order-tracking, owner.ulasan, dsb) yang tidak selalu konsisten untuk komplain.

---

## 6. Batasan yang Disepakati (untuk rencana lanjutan)

- Komplain tidak otomatis membuat refund; keputusan komplain = masuk akal, keputusan uang/refund = alur terpisah (lihat `alur-refund-dan-saldo.md`).
- Integrasi komplain↔refund diusulkan sebagai batch tersendiri (`integrasi-komplain-refund.md`), bukan bagian dari dokumen ini.