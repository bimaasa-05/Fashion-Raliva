# Plan: Atribusi Refund pada Rekap Karyawan (Raliva-Fashion)

> Dokumen handoff. Repo aktif: `C:\laragon\www\Raliva-Fashion`
> Status: **BELUM dikerjakan** — ikuti langkah di bawah sampai selesai, lalu verifikasi.
>
> Bagian dari suite dokumen komplain/refund:
> - `alur-komplain-lengkap.md` — sistem komplain saat ini
> - `alur-refund-dan-saldo.md` — sistem refund + proposal saldo pelanggan
> - `integrasi-komplain-refund.md` — rencana penyambungan komplain↔refund
> - `roadmap-komplain-refund.md` — peta status & keputusan seluruh suite

---

## 1. Latar Belakang

Kita sudah membangun **Rekap Karyawan** (mirip model kasir beautycare):

- **Owner** melihat pendapatan/pengeluaran/bersih tiap karyawan di tokonya (`/owner/rekap-karyawan`, plus export Excel & PDF).
- **Admin** melihat blok "Penjualanku" di halaman laporan (`/admin/laporan`).

Atribusi yang sudah jalan:
- Pendapatan karyawan = order berstatus `selesai` yang **pembayarannya diverifikasi admin tsb** (`PaymentVerification.verifier_id`, status `diterima`).
- Pengeluaran toko = `StoreExpense` dengan kolom baru `dibuat_oleh`.

Sisa yang belum sempurna: **atribusi refund / pengembalian dana**. Dokumen ini menjabarkan perbaikannya.

---

## 2. Keputusan (sudah disetujui user)

1. **Alur eskalasi (admin → Owner):** `reviewed_by` harus **tetap admin yang menangani** refund (admin yang menyetujui/eskalasi). Owner yang memutuskan final **tidak boleh menimpa** `reviewed_by`, sehingga refund tetap menjadi beban kontribusi admin tsb di rekap.
2. **Status yang dihitung:** refund dihitung sebagai pengeluaran karyawan hanya jika status **`selesai`** (dana benar-benar keluar, konsisten dengan laporan store-level & sistem saldo/wallet yang akan dibangun seperti beautycare). Refund `disetujui` yang belum tuntas **tidak** dihitung.
3. Tidak ada perubahan schema / migration (tidak dibutuhkan kolom baru).

---

## 3. Alur Refund Saat Ini (referensi)

| Tahap | Actor | Aksi | Status jadi | `reviewed_by` |
|---|---|---|---|---|
| 1 | Customer | Ajukan refund (`requested_by`=customer) | `requested` | NULL |
| 2 | Admin | `setujui` | `disetujui` | = Admin ✅ |
| 2 | Admin | `tolak` | `ditolak` | = Admin (tidak dihitung) |
| 2 | Admin | `eskalasi` | `escalated` | = Admin ✅ |
| 3 | Owner | `setujui`/`tolak` (dari `escalated`) | `disetujui`/`ditolak` | **HARUS TIDAK DITIMPA** (fix) |
| 4 | Owner/SuperAdmin | `selesaikan` (dari `disetujui`) | `selesai` (+ wallet decrement utk SuperAdmin) | tidak berubah ✓ |

Rute terkait di `routes/web.php`:
- Admin: `admin.pengembalian-dana.setujui/tolak/eskalasi` (baris ±370-373)
- Owner: `owner.pengembalian-dana.setujui/tolak/selesaikan` (baris ±484-487 & 500-502)

---

## 4. Perubahan Kode

### 4.1 `app/Services/KaryawanReportService.php`

`refundKaryawan()` saat ini (baris ±43-50):
```php
->where('refunds.reviewed_by', $userId)
->whereIn('refunds.status', [Refund::STATUS_DISETUJUI, Refund::STATUS_SELESAI])
```

Ubah menjadi **hanya `selesai`**:
```php
->where('refunds.reviewed_by', $userId)
->where('refunds.status', Refund::STATUS_SELESAI)
```

Jangan ubah method lain (`rekapKaryawan` memakai `refund` via `$this->refundKaryawan`).

### 4.2 `app/Http/Controllers/Owner/PengembalianDanaController.php`

Di `setujui()` (baris ±36-48) dan `tolak()` (baris ±65-82):

- **Hapus** `'reviewed_by' => Auth::id()` dari `$refund->update([...])`.
- Tambahkan guard defensif agar `reviewed_by` hanya diisi bila masih NULL (menjaga atribusi admin yang menangani; tetap aman jika suatu saat refund eskalasi masuk tanpa peninjau):
  ```php
  $data = [
      'status' => Refund::STATUS_DISETUJUI,   // atau STATUS_DITOLAK
      'selesai_pada' => now(),
  ];
  if (! $refund->reviewed_by) {
      $data['reviewed_by'] = Auth::id();
  }
  $refund->update($data);
  ```
- Perhatikan `tolak()` juga mengisi `alasan_penolakan` — pertahankan.

### 4.3 Teks bantuan (wording) — opsional tapi disarankan

- `resources/views/Owner/rekap-karyawan/index.blade.php` (baris ±33): ubah kalimat info agar sesuai — "Pengeluaran = refund selesai yang ditinjau karyawan + pengeluaran toko yang dicatatnya."
- `resources/views/Admin/laporan/index.blade.php` ("Pengeluaran Saya" section): samakan redaksi — "Pengeluaran saya = refund yang saya tinjau dan telah selesai (dana keluar) + pengeluaran toko yang saya catat. Hanya untuk toko yang ditugaskan (AdminContext)."

---

## 5. Verifikasi (JANGAN lewatkan)

Semua tes memakai pola transaksional (insert sementara → `rollBack`) supaya data DB tidak rusak. File tes berasa di luar repo (mis. `%TEMP%`) atau jalankan via `tinker`.

1. **Syntax check:** `php -l` untuk 2 file yang diubah (`KaryawanReportService.php`, `Owner/PengembalianDanaController.php`).
2. **Refund `selesai` masuk rekap:**
   - Boot app, `DB::beginTransaction()`.
   - Insert `Refund` status `selesai`, `reviewed_by` = admin uji (id dari `admin@raliva.test`), `order_id` dari store yang ditugaskan, `jumlah` = 500000.
   - Panggil `rekapKaryawan(userId, [storeId])` → harap `expense`/`pengeluaran` = 500000. `rollBack()`.
3. **Refund `disetujui` TIDAK dihitung:**
   - Insert Refund status `disetujui` dgn `reviewed_by` admin yang sama → `rekapKaryawan` harus **tidak** mengubah angka. `rollBack()`.
4. **Eskalasi tidak menimpa `reviewed_by`:**
   - Insert Refund status `escalated` dgn `reviewed_by` = admin(17), `order_id` milik store Owner-context.
   - `Auth::loginUsingId(ownerId)` lalu panggil `Owner\PengembalianDanaController::setujui` dalam transaksi.
   - Pastikan field `reviewed_by` di DB **tetap = 17** (bukan owner). `rollBack()`.
5. **Render/runtime:**
   - Start `php artisan serve` (mis. port 9001), highlight:
     - Login owner → `GET /owner/rekap-karyawan` harus 200.
     - `GET /owner/rekap-karyawan/export-excel` dan `/export-pdf` harus 200 + attachment.
     - Login admin → `GET /admin/laporan` harus 200, blok "Penjualanku" tampil.
   - Matikan server setelah selesai.
6. `php artisan view:cache` setelah semua edit (karena ada perubahan blade).

---

## 6. Dampak Data (ekspektasi)

- Refund legacy di DB (`requested`/`disetujui`/`selesai`, semuanya `reviewed_by` NULL) **tidak** teratribusi ke karyawan mana pun — wajar, tidak ada info penyetuju. Jangan di-backfill manual.
- Setelah admin menyetujui refund baru dan dana tuntas (`selesai`), otomatis masuk kolom Pengeluaran admin tsb di Rekap Karyawan & "Penjualanku".
- Konsistensi: Total Pengeluaran Rekap Karyawan kini sepadan dengan store-level (hanya `selesai`).

---

## 7. Di Luar Scope (temuan opsional)

- `Admin\PengembalianDanaController::index` dan action `setujui/tolak` **tidak** memfilter scope toko (`AdminContext::assignedStoreIds`) — hanya Owner yang punya `assertStoreOwnerScope`. Bisa dijadikan pekerjaan terpisah (potensi akses lintas toko).
- Sistem saldo/account (wallet) ala beautycare untuk alur refund-dana-keluar otomatis belum dibangun; saat ini `selesaikan` milik SuperAdmin yang decrement wallet (`WalletTransaction::JENIS_REFUND_KELUAR`).

---

## 8. Ringkasan File yang Disentuh

| File | Perubahan |
|---|---|
| `app/Services/KaryawanReportService.php` | `refundKaryawan` hanya status `selesai` |
| `app/Http/Controllers/Owner/PengembalianDanaController.php` | setujui/tolak jangan timpa `reviewed_by` (+guard NULL) |
| `resources/views/Owner/rekap-karyawan/index.blade.php` | wording info |
| `resources/views/Admin/laporan/index.blade.php` | wording blok Penjualanku |