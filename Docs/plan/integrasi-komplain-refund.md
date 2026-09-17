# Integrasi Komplain ↔ Refund — Rencana Batch

> Dibuat: 2026-09-17
> Rencana penyambungan sistem **komplain** dan **refund** yang saat ini berdiri sendiri (ditemukan saat audit `alur-komplain-lengkap.md` dan `alur-refund-dan-saldo.md`).
> Status: **RENCANA** — belum disetujui detail untuk dieksekusi. Ikuti langkah di bawah setelah disetujui.

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
| 1 | Refund dihitung di Rekap Karyawan **hanya saat status `selesai`** (dana benar-benar keluar). `disetujui` tidak dihitung. |
| 2 | Saat refund dieskalasi, `reviewed_by` **tetap admin penangan awal** — Owner tidak menimpa. |
| 3 | Tidak ada perubahan status komplain yang dipaksakan oleh refund: komplain dan refund tetap dua entitas yang bisa berdiri sendiri. |
| 4 | Semua dokumen plan di `Docs\plan` gaya kebab-case tanpa angka; file ini bagian dari suite komplain/refund. |

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

### 3.4 Konsistensi `selesaikan` (opsional, butuh keputusan user)

Dua opsi (pilih satu):
- **A. Standarisasi Owner seperti SA, tanpa wallet:** Owner wajib `file_bukti` juga, tapi TANPA decrement `Wallet.saldo_tersedia` (karena alur dana toko di luar sistem). Keuntungan: bukti seragam.
- **B. Hitung dana via saldo pelanggan (future):** setelah sistem saldo pelanggan (`alur-refund-dan-saldo.md` §5) dibangun, `selesaikan` = debit wallet toko + kredit saldo pelanggan, berlaku seragam untuk Owner & SA.

> Belum diputuskan. Rekomendasi: kerjakan B sebagai bagian batch saldo; untuk saat ini biarkan A sebagai penyesuaian kecil bila disetujui.

### 3.5 Status order `refund` (inkonsistensi pendapatan)

- Proposal: saat refund **full** mencapai `selesai`, dan tidak ada sisa item yang dikirim, transisikan order ke `Order::STATUS_REFUND` di dalam `selesaikan` (SA) dan `selesaikan` (Owner).
- Dampak yang perlu dikaji ulang: `confirm()` dan `STATUS_STEPS` tracking, laporan pendapatan (`KaryawanReportService` memakai `orders.status = selesai`), dashboard, filter data pesanan.
- **Rekomendasi:** jangan dieksekusi sebelum kajian dampak laporan selesai (lapisan kedua batch ini).

---

## 4. File yang Disentuh (ringkas)

| File | Perubahan |
|---|---|
| `database/migrations/*_add_complaint_id_to_refunds_table.php` (baru) | kolom + FK + index |
| `app/Models/Refund.php` | relasi `complaint()` |
| `app/Models/Complaint.php` | relasi `refund()` |
| `app/Http/Controllers/Customer/OrderTrackingController.php` | `storeRefund` simpan `complaint_id` (input opsional) |
| View thread komplain (customer) | tombol + modal "Ajukan Refund" + badge status refund |
| `app/Http/Controllers/Owner/PengembalianDanaController.php` | guard `reviewed_by` (rekap-karyawan §4.2) |
| (opsional) `SuperAdmin/Owner selesaikan` | standarisasi bukti / sistem saldo |

---

## 5. Urutan Kerja yang Diusulkan

1. Batch atribusi rekap karyawan dulu (`refund-rekap-karyawan.md`) — berujung pada keputusan "refund dihitung hanya `selesai`" yang sudah final.
2. Migrasi `complaints.complaint_id` + relasi.
3. Tombol ajukan refund dari thread komplain.
4. (Pilih 3.4 A/B) konsistensi `selesaikan`.
5. (Setelah kajian) transisi order → `refund` dan dampak laporan.
6. (Terpisah) sistem saldo pelanggan.

## 6. QA Ideas

- Komplain dibuat → ajukan refund dari thread → `refunds.complaint_id` terisi.
- Guard duplikat: ajukan refund kedua dari thread → ditolak UI (+ backend).
- Refund `escalated` → Owner `setujui` → `reviewed_by` tetap admin.
- Refund `selesai` tampil di Rekap Karyawan; `disetujui` tidak.
- (Bila 3.5) order full-refund berubah `refund`, laporan pendapatan turun sesuai.