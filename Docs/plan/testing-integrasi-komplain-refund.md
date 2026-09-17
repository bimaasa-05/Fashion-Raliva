# Testing — Integrasi Komplain ↔ Refund + Selesaikan Owner (Opsi A)

> Dibuat: 2026-09-17
> Panduan pengujian untuk perubahan yang sudah dikerjakan pada batch integrasi komplain/refund. Ikuti langkah A→B→C→D secara berurutan. Semua langkah memakai persiapan default Laravel + seeder.
> Status: **SIAP DIJALANKAN** — acuan untuk teman yang akan menguji.

---

## 1. Tujuan & Lingkup Test

Perubahan yang harus diverifikasi:

| No | Fitur | Status |
|---|---|---|
| 1 | Kolom `refunds.complaint_id` + relasi `Refund.complaint` ↔ `Complaint.refund` | Selesai |
| 2 | Customer mengajukan refund **dari dalam thread komplain** (tombol + modal + badge status) | Selesai |
| 3 | `storeRefund` menerima `complaint_id` + guard komplain milik customer & order yang sama | Selesai |
| 4 | Owner menyelesaikan refund (Opsi A): wajib `file_bukti`, **tanpa** mengurangi saldo wallet | Selesai |
| 5 | Duplikat pengajuan refund tetap diblokir | Selesai |

**BUKAN bagian test ini** (di luar scope, dikerjakan orang lain):
- Scope Admin `AdminContext` pada `Admin\PengembalianDanaController`.
- `KaryawanReportService` + rekap refund (lihat `Docs/plan/refund-rekap-karyawan.md`).
- Transisi `Order::STATUS_REFUND` & decrement saldo pelanggan (lapisan berikutnya).
- `SuperAdmin/pengembalian-dana` (pola lama — tidak diubah).

---

## 2. Prasyarat

1. Pastikan migrasi sudah jalan:

   ```powershell
   php artisan migrate
   ```

2. Jalankan server dev:

   ```powershell
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`.

3. Akun login (akun seed `UserSeeder`, password semua `123`):

   | Peran | Email |
   |---|---|
   | Customer | `c@gmail.com` |
   | Owner | `o@gmail.com` |

4. Kebutuhan data uji:

   - Minimal satu **komplain** yang terkait **order berstatus `dikirim` atau `selesai`** dan **belum punya refund aktif** (untuk Test B positif).
   - Minimal satu komplain untuk **order lain** (untuk Test B negatif).
   - Minimal satu **refund berstatus `disetujui`** milik toko owner (untuk Test C).
   - Jika belum ada, bisa dibuat cepat lewat `php artisan tinker` atau lewat UI order-tracking.

Status refund yang dikenal: `requested`, `escalated`, `disetujui`, `ditolak`, `selesai`. Pengajuan refund hanya diizinkan saat status order `dikirim`/`selesai` dan belum ada refund aktif (`requested`/`escalated`/`disetujui`).

---

## 3. Test A — Soak Test Otomatis (Logika Backend)

Script memuat aplikasi Laravel, menjalankan serangkaian asersi, lalu **meng-rollback semua transaksi** → data asli di database **tidak berubah**. Aman dijalankan berulang kali.

**Cara menjalankan** (dari root project):

```powershell
php C:\Users\ogie\AppData\Local\Temp\opencode\soak-refund.php
```

> Jika file itu belum ada di komputer teman, mintalah kopian dari pemilik repo (atau buat ulang berdasarkan bagian 3.1–3.6 berikut).

**Output yang diharapkan** (contoh hasil run terakhir yang lulus):

```
=== 1. SKEMA & RELASI MODEL ===
[PASS] refunds.complaint_id ada
[PASS] Refund fillable complaint_id
[PASS] Refund::complaint relation
[PASS] Complaint::refund relation
=== 2. INVENTORI (cari order eligible + payment utk test positif) ===
[...]
=== 3. POSITIF storeRefund dgn complaint_id ===
[PASS] storeRefund ok (...)
[PASS] refund dibuat dgn complaint_id — refund #N
[PASS] refund->complaint_id == komplain
[PASS] refund->order_id cocok
[PASS] refund->payment_id cocok
[PASS] relasi refund->complaint mengarah balik
[PASS] relasi komplain->refund mengarah ke refund
=== 4. NEGATIF guard (complaint utk order lain) ===
[PASS] guard tolak complaint utk order lain (refund tidak bertambah) — err=none
=== 5. OWNER selesaikan POSITIF (opsi A, tanpa wallet) ===
[PASS] selesaikan ok
[PASS] status jadi selesai
[PASS] file_bukti tersimpan — path=bukti-refund/N/xxxx.jpg
[PASS] reviewed_by tetap 17 — riil=17
[PASS] wallet TIDAK berubah (opsi A) — sebelum=... sesudah=...
=== 6. OWNER selesaikan NEGATIF (tanpa file) ===
[PASS] tanpa file -> ValidationException — Illuminate\Validation\ValidationException
[PASS] status tetap disetujui
[PASS] tidak ada ActivityLogger refund.complete utk r2
=== 7. DUP (order dgn refund aktif tetap ditolak) ===
[PASS] refund dup ditolak (jml refund tidak bertambah) — err=none

FAIL count: 0
ROLLBACK OK
```

**Kriteria lulus:** semua `[PASS]`, `FAIL count: 0`, dan ada `ROLLBACK OK`. Jika ada `[FAIL]`, laporkan label + detail-nya.

### 3.1–3.6 Apa saja yang diuji

| Bagian | Yang diuji |
|---|---|
| 1. Skema & relasi | Kolom `complaint_id` ada; terisi di `$fillable`; method `refund()` & `complaint()` |
| 3. Positif | `storeRefund` dengan `complaint_id` tersimpan benar (`complaint_id`, `order_id`, `payment_id` cocok; relasi dua arah benar; file upload virtual terkurasi) |
| 4. Negatif guard | Komplain milik order lain → refund tetap 0 |
| 5. Owner positif | Status → `selesai`, `file_bukti` tersimpan, `reviewed_by` tidak ditimpa, **wallet tidak berubah** |
| 6. Owner negatif | Tanpa `file_bukti` → `ValidationException`, status tetap `disetujui`, tidak ada log |
| 7. Duplikat | Order dengan refund aktif → pengajuan baru ditolak |

---

## 4. Test B — Manual UI Customer (Refund dari Thread Komplain)

1. Login sebagai Customer `c@gmail.com` / `123`.
2. Buka `http://127.0.0.1:8000/customer/komplain`.
3. **Buka thread komplain** yang order-nya berstatus `dikirim`/`selesai` dan belum ada refund aktif.
4. Periksa **badge status refund** di header chat (id `#chat-refund-badge`):
   - Belum ada refund → badge tersembunyi atau label "-".
   - Sudah ada refund → menampilkan label status (mis. `Requested`, `Disetujui`, `Selesai`).
5. Buka **menu "more"** di header chat → item **"Ajukan Refund"** (id `#chat-more-item-refund`) harus muncul.
6. Klik item tersebut → modal refund (id `#modal-refund`) terbuka, dan `complaint_id` sudah terisi otomatis (field tersembunyi).
7. Isi form modal:
   - **Tipe refund**: `Penuh` (full) atau `Sebagian` (partial).
   - **Jumlah**: harus **≥ 1** dan **tidak boleh melebihi total pesanan** (`grand_total`). Coba isi melebihi total → harus ditolak.
   - **Alasan**: minimal **20 karakter**, maks 2000.
   - **File bukti**: wajib, gambar `jpg/jpeg/png`, maks **4 MB** (4096 KB). Coba upload file >4MB atau format lain → harus ditolak.
   - **Deskripsi bukti** (opsional, max 1000 karakter).
8. Submit → redirect ke detil order + toast sukses "Pengajuan refund terkirim".
9. **Verifikasi data**:

   ```powershell
   php artisan tinker
   ```
   ```php
   $r = App\Models\Refund::where('order_id', <ORDER_ID>)->latest('refund_id')->first();
   var_dump($r->complaint_id);   // harus = complaint_id thread yang dibuka
   var_dump($r->complaint->complaint_id); // relasi balik harus sama
   ```
   - Buka `storage/app/public/bukti-refund-request/{order_id}/` → file bukti tersimpan.
   - Buka kembali thread → badge berubah menjadi `Requested`, dan item "Ajukan Refund" **tidak muncul lagi** (sudah ada refund aktif).

10. **Uji negatif**: buka thread komplain milik **order lain** → item "Ajukan Refund" **tidak muncul**. (Jaga-jaga: kalau paksa POST manual `complaint_id` dari order lain, server harus menolak dengan toast "Komplain tidak valid untuk pesanan ini".)

> Catatan: pengajuan refund juga tetap bisa lewat detil order di `/customer/order-tracking` (tanpa `complaint_id` → kolom tetap `NULL`, refund tetap jalan seperti biasa).

---

## 5. Test C — Manual UI Owner (Selesaikan, Opsi A)

1. Login sebagai Owner `o@gmail.com` / `123`.
2. Buka `http://127.0.0.1:8000/owner/pengembalian-dana`.
3. Pada seksi **"Menunggu Penyelesaian Anda"** harus tampil refund berstatus `disetujui` milik toko ini, masing-masing dengan tombol **Selesaikan** (buka modal `#modal-selesaikan-{kode}`).
   - Jika bukti *request* dari customer ada → ada link untuk melihat buktinya (open in new tab).
4. **Uji validasi — tanpa file**: klik **Selesaikan** lalu submit tanpa memilih file → browser/form menolak (input `required`) dan/atau server mengembalikan error validasi `file_bukti` wajib.
5. **Upload bukti**: pilih file `jpg/jpeg/png/pdf`, maks **5 MB**. Coba file >5MB/format lain → ditolak.
   - Isi **Deskripsi bukti** (opsional, max 1000 karakter, cth. "Transfer ke rekening customer").
6. Submit → status refund menjadi **`Selesai`** dan kartu berpindah keluar dari "Menunggu Penyelesaian Anda".
7. **Verifikasi**:
   - File bukti tersimpan di `storage/app/public/bukti-refund/{refund_id}/`.
   - Kode ini **tidak** memotong saldo toko (Opsi A):

     ```powershell
     php artisan tinker
     ```
     ```php
     App\Models\Wallet::where('store_id', <STORE_ID_OWNER>)->first(); // saldo_tersedia TIDAK berubah
     ```
   - Ada log aktivitas:

     ```php
     App\Models\ActivityLog::where('aksi', 'refund.complete')->latest()->first();
     ```
   - Notifikasi muncul untuk owner (judul terkait pengajuan).

---

## 6. Test D — Sanity (Konfigurasi & Routing)

1. **Route tidak duplikat** — masing-masing route refund harus muncul **tepat 1 baris**:

   ```powershell
   php artisan route:list | Select-String "selesaikan"
   ```
   Hasil yang benar:
   - `POST owner/pengembalian-dana/{refund}/selesaikan`
   - `POST superadmin/pengembalian-dana/{refund}/selesaikan`
   (sebelumnya ada duplikat blok route owner yang sudah dihapus.)

2. **Blade ter-kompile tanpa error**:

   ```powershell
   php artisan view:cache
   ```

3. **Tidak ada syntax error** pada file yang berubah:

   ```powershell
   php -l app/Http/Controllers/Customer/OrderTrackingController.php
   php -l app/Http/Controllers/Customer/KomplainController.php
   php -l app/Http/Controllers/Owner/PengembalianDanaController.php
   php -l app/Models/Refund.php
   php -l app/Models/Complaint.php
   php -l database/migrations/2026_09_17_100000_add_complaint_id_to_refunds_table.php
   ```
   Semua harus menampilkan `No syntax errors detected`.

---

## 7. Actions / Endpoint Terkait (Referensi Cepat)

| Method | URL | Nama Route | Fungsi |
|---|---|---|---|
| GET | `customer/komplain` | `customer.komplain` | Daftar & thread komplain customer |
| GET | `customer/order-tracking` | `customer.order-tracking` | Detil order + form refund (lama) |
| POST | `customer/refund` | `customer.refund.store` | Simpan pengajuan refund (kini menerima `complaint_id`) |
| GET | `owner/pengembalian-dana` | `owner.pengembalian-dana` | Manajemen refund owner (ada seksi "Menunggu Penyelesaian Anda") |
| POST | `owner/pengembalian-dana/{refund}/selesaikan` | `owner.pengembalian-dana.selesaikan` | Selesaikan refund — wajib `file_bukti`, tanpa wallet |

**Validasi utama `storeRefund`**: `tipe_refund` ∈ `full,partial`; `jumlah` ≥1 & ≤ grand_total; `alasan` min 20 & max 2000; `file_bukti_request` wajib image jpg/jpeg/png ≤ 4MB; `deskripsi_bukti_request` max 1000; `complaint_id` opsional tapi harus `exists` dan dipastikan milik customer & order yang sama.

**Validasi `selesaikan` (Owner)**: `file_bukti` wajib (mimes jpg/jpeg/png/pdf, max 5120 KB); `deskripsi_bukti` max 1000. Hanya refund berstatus `disetujui` yang boleh diselesaikan → status jadi `selesai`.

---

## 8. Troubleshooting

| Gejala | Kemungkinan & Solusi |
|---|---|
| Soak test output "tidak ada order eligible" | Belum ada order `dikirim`/`selesai` yang punya `checkout.payment` & tanpa refund aktif. Buat order baru + payment (lewat checkout/UI penjualan) lalu ulangi. |
| Badge/tombol "Ajukan Refund" tidak muncul padahal order eligible | Pastikan membuka **thread komplain**, bukan halaman lain; pastikan `data-open-refund-aktif="0"` dan `data-open-order-elig="1"` di kartu (inspeksi elemen). |
| Owner tidak melihat seksi "Menunggu Penyelesaian Anda" | Pastikan ada refund `disetujui` milik **toko owner tsb**; Owner hanya melihat data toko sendiri. |
| `view:cache` gagal | Ada salah satu file blade error sintaks — cek pesan, perbaiki, ulangi. |
| Login gagal | Akun seed mungkin berbeda di lingkungan teman; gunakan akun dari `UserSeeder` atau buat akun peran sesuai. |

---

## 9. Checklist Ringkas (buat teman)

- [ ] `php artisan migrate` sukses
- [ ] Soak test → `FAIL count: 0` + `ROLLBACK OK`
- [ ] `route:list` → route `selesaikan` tidak duplikat
- [ ] `php -l` semua file → `No syntax errors detected`
- [ ] Customer: badge, tombol "Ajukan Refund", modal, submit sukses, `complaint_id` terisi
- [ ] Customer: komplain order lain → item tidak muncul; jumlah > total → ditolak; file >4MB → ditolak
- [ ] Owner: Selesaikan tanpa file → ditolak; dengan bukti → status `Selesai`; wallet tetap