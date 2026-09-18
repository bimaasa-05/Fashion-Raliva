# Testing — Integrasi Komplain ↔ Refund + Selesaikan Satu Jalur (Batch Refund)

> Dibuat: 2026-09-17 · Diperbarui: 2026-09-18
> Panduan pengujian untuk batch refund/komplain: selesaikan satu jalur via `RefundCompletionService`, rekap karyawan (refund hanya `selesai`), `reviewed_by` tidak ditimpa Owner, scope Admin `AdminContext`, transisi `Order::STATUS_REFUND` + penyesuaian laporan (R1). Ikuti langkah A→B→C→D→E berurutan.
> Status: **SIAP DIJALANKAN**

---

## 1. Tujuan & Lingkup Test

| No | Fitur | Status |
|---|---|---|
| 1 | Kolom `refunds.complaint_id` + relasi `Refund.complaint` ↔ `Complaint.refund` | Selesai |
| 2 | Customer mengajukan refund dari dalam thread komplain (`complaint_id`) + guard komplain milik customer & order sama | Selesai |
| 3 | **Selesaikan Satu Jalur**: Owner & Super Admin sama-sama memanggil `RefundCompletionService` → potong wallet toko, status `selesai`, flip order full-refund ke `refund` | Selesai |
| 4 | `reviewed_by` tidak ditimpa: Owner `setujui`/`tolak` hanya mengisi bila masih NULL (escalated) | Selesai |
| 5 | Scope Admin `AdminContext`: index difilter + aksi `setujui`/`tolak`/`eskalasi` diblokir 403 di luar toko | Selesai |
| 6 | Rekap karyawan: `refund` hanya menghitung refund `selesai`; pendapatan pakai `whereIn([selesai, refund])` | Selesai |
| 7 | Laporan R1: revenue tidak "terpotong ganda" saat order full-refund; refund jadi expense terpisah | Selesai |
| 8 | Duplikat pengajuan refund tetap diblokir | Selesai |

Model hasil akhir ini dibahas lengkap di `Docs/plan/alur-refund-dan-saldo.md` (`Order::STATUS_REFUND` kini "aktif").

---

## 2. Prasyarat

1. Migrasi sudah jalan (`php artisan migrate`).
2. Jalankan server dev: `php artisan serve` → `http://127.0.0.1:8000`.
3. Akun seed:
   | Peran | Email | Password |
   |---|---|---|
   | Super Admin | `sa@gmail.com` | `123` |
   | Owner | `o@gmail.com` | `123` |
   | Admin toko | `admin@raliva.test` | `password` |
   | Customer | `c@gmail.com` | `123` |
4. Kebutuhan data uji: order `dikirim`/`selesai` yang punya `checkout.payment` & belum ada refund aktif (untuk Test B/D); kalau belum ada, buat lewat UI checkout/detil order.

---

## 3. Test A — Soak Test Otomatis (Logika Backend)

Script memuat aplikasi Laravel, menjalankan asersi, lalu **rollback semua transaksi** → data asli tidak berubah.

```powershell
php C:\Users\ogie\AppData\Local\Temp\opencode\soak-refund.php
```

**Output yang diharapkan** (run terakhir: semuanya PASS):

```
=== 1. SKEMA & RELASI MODEL ===            [PASS] x4
=== 2. INVENTORI (cari order eligible) ===
=== 3. POSITIF storeRefund dgn complaint_id ===            [PASS] x7
=== 4. NEGATIF guard (complaint utk order lain) ===        [PASS]
=== 5. OWNER setujui eskalasi — TIDAK menimpa reviewed_by ===  [PASS] x2
=== 6. OWNER selesaikan POSITIF — SATU JALUR (potong wallet + status selesai) ===  [PASS] x8
=== 7. OWNER selesaikan NEGATIF (tanpa file) ===  [PASS] x4
=== 8. SALDO TOKO NEGATIF (jumlah > saldo) — rollback & file dibersihkan ===  [PASS] x4
=== 9. FLIP ORDER STAT_REFUND (full) vs TETAP SELESAI (partial) ===  [PASS] x12 (termasuk R1/R2/R3)
=== 9b. DOUBLE-COMPLETE ditolak (status sudah berubah) === [PASS]
=== 10. REKAP KARYAWAN — refund hanya selesai + reviewed_by admin ===  [PASS] x2
=== 11. SCOPE AdminContext (index & aksi guard 403) ===  [PASS] x3
=== 7b. DUP (order dgn refund aktif tetap ditolak) ===  [PASS]

FAIL count: 0
ROLLBACK OK
```

**Kriteria lulus:** semua `[PASS]`, `FAIL count: 0`, `ROLLBACK OK`. Jika ada `[FAIL]`, laporkan label + detail-nya.

### Apa yang diuji tiap seksi

| Seksi | Yang diuji |
|---|---|
| 1 | Kolom `complaint_id`, `$fillable`, relasi dua arah |
| 3 | `storeRefund` dengan `complaint_id` tersimpan benar (`complaint_id`, `order_id`, `payment_id`, relasi) |
| 4 | Komplain milik order lain → refund tidak bertambah |
| 5 | Owner `setujui` refund `escalated`: status → `disetujui`, `reviewed_by` **tetap admin** (17) |
| 6 | Owner `selesaikan` (satu jalur): status → `selesai`, `file_bukti` tersimpan, wallet toko **terpotong**, `WalletTransaction REFUND_KELUAR`, `ActivityLog refund.complete`, `reviewed_by` tetap 17 |
| 7 | Tanpa `file_bukti` → `ValidationException`, status tetap, wallet tidak berubah, tanpa log |
| 8 | Jumlah > saldo → `RuntimeException saldo tidak cukup`, wallet tidak berubah, file bukti dihapus (rollback) |
| 9 | Refund **full** = grand_total → order flip `STATUS_REFUND` + log `order.refunded`; refund **partial** → order tetap `selesai`. Lalu: R1 `whereIn([selesai,refund])` revenue **tidak turun**, revenue lama (`selesai` saja) turun = grand_total, sum refund (expense) naik; R2 order full keluar dari count selesai & masuk count refund |
| 9b | `complete()` kedua → `RuntimeException status sudah berubah` |
| 10 | Rekap karyawan: delta refund = jumlah refund `selesai` saja; refund `disetujui` tidak dihitung |
| 11 | Scope Admin kosong (assignment dinonaktifkan sementara): index tidak menampilkan refund itu, `setujui` → 403, status tetap `requested` |
| 7b | Order yang masih punya refund aktif → pengajuan duplikat ditolak (rollback committee count) |

---

## 4. Test B — Manual UI Customer (Refund dari Thread Komplain)

1. Login `c@gmail.com` / `123`, buka `http://127.0.0.1:8000/customer/komplain`.
2. Buka thread komplain untuk order `dikirim`/`selesai` tanpa refund aktif:
   - Badge status refund di header chat (`#chat-refund-badge`) → tampil setelah ada refund.
   - Menu "more" → **"Ajukan Refund"** (`#chat-more-item-refund`) muncul.
3. Klik → modal refund (`#modal-refund`) terbuka, `complaint_id` terisi otomatis. Isi form: tipe `Penuh`/`Sebagian`, jumlah ≥1 & ≤ `grand_total`, alasan ≥20 karakter, `file_bukti_request` gambar jpg/jpeg/png ≤4MB. Submit → redirect detil order + toast "Pengajuan refund terkirim".
4. Verifikasi: `$r->complaint_id` = complaint thread; file di `storage/app/public/bukti-refund-request/{order_id}/`; badge berubah `Requested`.
5. Komplain order lain → item tidak muncul; kalau paksa POST manual `complaint_id` order lain → ditolak.

---

## 5. Test C — Manual UI Owner / Super Admin (Selesaikan Satu Jalur & Flip)

1. Login `o@gmail.com` / `123` → `http://127.0.0.1:8000/owner/pengembalian-dana`. Pada seksi **"Menunggu Penyelesaian Anda"** tampil refund `disetujui` milik toko ini + tombol **Selesaikan**.
2. **Uji validasi**: submit tanpa file → ditolak (`file_bukti` wajib). Upload `jpg/jpeg/png/pdf` ≤5MB + deskripsi opsional → submit.
3. **Hasil**:
   - Status refund → `Selesai`, kartu pindah dari "Menunggu Penyelesaian Anda".
   - **Wallet toko terpotong** sesuai `jumlah` (satu jalur; `superadmin` juga memotong — bukan lagi cara lama "tanpa wallet"):

     ```powershell
     php artisan tinker
     ```
     ```php
     $w = App\Models\Wallet::where('store_id', <STORE_ID>)->first(); // saldo_tersedia turun
     App\Models\WalletTransaction::where('refund_id', <REFUND_ID>)->latest()->first(); // REFUND_KELUAR
     App\Models\ActivityLog::where('aksi', 'refund.complete')->latest()->first();
     ```
   - Jika refund **full** dan `jumlah >= grand_total` saat status order `dikirim`/`selesai` → **order berubah status `refund`** dan ada log `order.refunded`. Refund **partial** → order tetap di status semula.
4. Uji ulang selesaikan pada refund yang sama → ditolak ("status sudah berubah").

---

## 6. Test D — Manual UI Admin & Laporan (Scope + R1)

1. Login `admin@raliva.test` / `password` → `http://127.0.0.1:8000/admin/pengembalian-dana`: hanya refund toko yang ditugaskan yang tampil.
   - Kalau hanya ada satu toko di DB, uji scope dengan menonaktifkan sementara assignment: **Admin → Penugasan Toko**, set staff `admin@raliva.test` nonaktif → halaman refund jadi kosong & aksi mun = 403. (Undo kembali setelahnya.)
2. `http://127.0.0.1:8000/admin/laporan` → Ringkasan: **Total Pendapatan** tidak berubah drastis saat ada order full-refund (revenue memakai `whereIn([selesai, refund])`), jumlah **refund** tampil terpisah.
3. Owner `http://127.0.0.1:8000/owner/pesanan` → chip/kartu **Refund** ada, filter refund menampilkan order `refund`. `http://127.0.0.1:8000/owner/laporan` sama dengan poin 2.
4. Owner `http://127.0.0.1:8000/owner/rekap-karyawan` → kolom **Refund** hanya menghitung refund `selesai` (yang baru `disetujui` belum dihitung).

---

## 7. Test E — Sanity (Konfigurasi & Routing)

```powershell
# route selesaikan tidak duplikat → harus TEPAT 2 baris
php artisan route:list | Select-String "selesaikan"
# expected: POST owner/pengembalian-dana/{refund}/selesaikan
#           POST superadmin/pengembalian-dana/{refund}/selesaikan

php artisan view:cache    # blade ter-kompile tanpa error

php -l app/Services/RefundCompletionService.php
php -l app/Services/KaryawanReportService.php
php -l app/Http/Controllers/Owner/PengembalianDanaController.php
php -l app/Http/Controllers/Admin/PengembalianDanaController.php
php -l app/Http/Controllers/SuperAdmin/PengembalianDanaController.php
php -l app/Http/Controllers/Owner/LaporanController.php
php -l app/Http/Controllers/Admin/LaporanController.php
php -l app/Http/Controllers/SuperAdmin/LaporanController.php
php -l app/Http/Controllers/Owner/PesananController.php
php -l app/Exports/OwnerLaporanRingkasanSheet.php
php -l app/Exports/OwnerLaporanPeriodeSheet.php
# Semua: No syntax errors detected
```

**HTTP smoke check** (semua halaman harus 200 setelah login):
- Admin: `/admin/pengembalian-dana`, `/admin/laporan`
- Owner: `/owner/pengembalian-dana`, `/owner/laporan`, `/owner/pesanan`, `/owner/rekap-karyawan`
- Super Admin: `/superadmin/pengembalian-dana`, `/superadmin/laporan`

---

## 8. Actions / Endpoint Terkait (Referensi Cepat)

| Method | URL | Fungsi |
|---|---|---|
| POST | `customer/refund` | Simpan pengajuan refund (menerima `complaint_id`) |
| POST | `admin/pengembalian-dana/{refund}/setujui|tolak|eskalasi` | Aksi admin (guard `AdminContext`) |
| POST | `owner/pengembalian-dana/{refund}/setujui|tolak` | Aksi owner — hanya isi `reviewed_by` bila masih NULL |
| POST | `owner/pengembalian-dana/{refund}/selesaikan` · `superadmin/pengembalian-dana/{refund}/selesaikan` | **Satu jalur** → `RefundCompletionService::complete()` |

**Validasi `selesaikan`**: `file_bukti` wajib (jpg/jpeg/png/pdf, max 5120 KB); `deskripsi_bukti` max 1000; hanya refund `disetujui` yang boleh → jadi `selesai`.

---

## 9. Troubleshooting

| Gejala | Kemungkinan & Solusi |
|---|---|
| Soak: seksi 2 "tidak ada order eligible" | Belum ada order `dikirim`/`selesai` ber-`checkout.payment` tanpa refund aktif. Buat order + payment lalu ulangi. |
| Soak seksi 11 skip | Admin menangani semua toko → asersi dijalankan lewat "nonaktifkan sementara assignment" (sudah otomatis di script). |
| `view:cache` gagal | Blade error sintaks — perbaiki lalu ulangi. |
| Login `admin@raliva.test` gagal | Password staff seed = `password` (bukan `123`). |

---

## 10. Checklist Ringkas

- [ ] Soak test → `FAIL count: 0` + `ROLLBACK OK`
- [ ] `route:list` → `selesaikan` persis 2 baris (owner + superadmin)
- [ ] `php -l` semua file → `No syntax errors detected`; `view:cache` sukses
- [ ] Halaman Admin/Owner/Super Admin → 200
- [ ] Owner selesaikan: wallet terpotong, status `selesai`, flip full → order `refund`; partial → tetap
- [ ] Owner setujui tidak menimpa `reviewed_by` admin
- [ ] Admin scope: 403 di luar toko
- [ ] Rekap karyawan: refund hanya `selesai`
- [ ] Laporan: revenue tidak double-deduct saat order full-refund