# Penjelasan Alur Pesanan Lengkap — Raliva Fashion

> Dibuat: 2026-09-15  
> Dokumentasi alur pesanan dari Customer memesan sampai Customer menerima pesanan.

---

## Daftar Isi

1. [Status yang Digunakan](#status-yang-digunakan)
2. [Alur Lengkap (Diagram)](#alur-lengkap-diagram)
3. [Detail Per Tahap](#detail-per-tahap)
4. [Role & Aksi](#role--aksi)
5. [Path Expired/Batal](#path-expiredbatal)
6. [Bug yang Ditemukan & Diperbaiki](#bug-yang-ditemukan--diperbaiki)

---

## Status yang Digunakan

### Order Status

| Status | Deskripsi | Siapa yang set |
|--------|-----------|---------------|
| `pending_payment` | Menunggu pembayaran | Customer checkout |
| `menunggu_produksi` | Pembayaran diverifikasi, menunggu Admin input bahan | Admin verifikasi |
| `diproses` | Admin sudah input bahan + tanggal produksi | Admin proses |
| `menunggu_qc` | Produksi selesai, menunggu QC + packing | Produksi selesai |
| `siap_kirim` | QC lulus + packing selesai, siap dikirim Admin | Produksi QC |
| `dikirim` | Admin input resi + kirim | Admin kirim |
| `selesai` | Customer konfirmasi terima | Customer confirm |
| `dibatalkan` | Dibatalkan (expired/cancelled) | System/Admin |

### Payment Status

| Status | Deskripsi |
|--------|-----------|
| `pending` | Belum upload bukti |
| `menunggu_verifikasi` | Customer upload bukti, menunggu Admin |
| `terverifikasi` | Admin setujui |
| `ditolak` | Admin tolak, customer upload ulang |
| `kadaluarsa` | Melewati batas waktu (auto) |

### Checkout Status

| Status | Deskripsi |
|--------|-----------|
| `pending` | Checkout dibuat, belum dibayar |
| `dibayar` | Pembayaran diverifikasi |
| `kadaluarsa` | Auto-expire |
| `selesai` | Order selesai |

### Shipment Status

| Status | Deskripsi |
|--------|-----------|
| `pending` | Resi belum diinput |
| `diproses` | Resi disimpan, belum dikirim |
| `dikirim` | Sudah dikirim |
| `diterima` | Customer konfirmasi terima |

---

## Alur Lengkap (Diagram)

```
Customer Checkout
  Checkout: pending
  Order: pending_payment
  Payment: pending (batas 24 jam)
        │
        ▼
Customer Upload Bukti Bayar
  Payment: menunggu_verifikasi
        │
        ├── Admin Tolak ──► Payment: ditolak (customer upload ulang)
        │
        ▼
Admin Verifikasi Pembayaran (setujui)
  Payment: terverifikasi
  Checkout: dibayar
  Order: menunggu_produksi
  Notifikasi → Produksi: "Pesanan Siap Diproduksi"
        │
        ▼
Admin Proses Pesanan (input bahan + tanggal produksi)
  Order: diproses
  Bahan: tersimpan di production_order_bahan
  Tanggal: tgl_mulai_produksi, tgl_berakhir_produksi
  Notifikasi → Produksi: "Pesanan Diproses"
        │
        ├── Produksi Tolak ──► Order: menunggu_produksi (balik ke Admin)
        │                       + catatan_tolak
        │
        ▼
Produksi Accept (mulai produksi)
  produksi_dimulai_pada: now()
  Notifikasi → Admin: "Produksi Diterima"
        │
        ▼
Produksi Selesai (input jumlah berhasil/gagal)
  Order: menunggu_qc
  jumlah_berhasil, jumlah_gagal: tersimpan
  Notifikasi → Admin: "Produksi Selesai — Menunggu QC"
        │
        ▼
Produksi QC + Packing (input lulus/gagal + catatan)
  Order: siap_kirim
  QualityCheck record dibuat
  tanggal_qc, tanggal_packing: now()
  Notifikasi → Admin: "Produk Siap Dikirim"
        │
        ▼
Admin Input Resi (pilih kurir + nomor resi)
  Shipment: diproses
        │
        ▼
Admin Kirim (klik kirim)
  Shipment: dikirim
  Order: dikirim
  Notifikasi → Customer: "Pesanan Dikirim" (+ kurir + resi)
        │
        ▼
Customer Konfirmasi Terima
  Order: selesai
  Shipment: diterima
  Wallet: credit ke saldo owner
  Notifikasi → Owner: "Pesanan Selesai"
```

---

## Detail Per Tahap

### Tahap 1: Customer Checkout

**Role:** Customer  
**File:** `app/Http/Controllers/Customer/CheckoutController.php → store()`

1. Customer pilih produk → checkout
2. Input: nama penerima, alamat, kota, provinsi, kode pos, metode pengiriman
3. Kalau guest: auto-buat akun Customer (password default `Raliva123`)
4. Buat `Checkout` (status: `pending`)
5. Buat `Order` per toko (status: `pending_payment`, nomor: `RLV-{storeId}-{hash}`)
6. Buat `OrderItem` per produk
7. Buat `Payment` (status: `pending`, batas waktu: +24 jam)
8. Notif ke customer + owner toko
9. Redirect ke halaman bayar

---

### Tahap 2: Customer Upload Bukti Bayar

**Role:** Customer  
**File:** `app/Http/Controllers/Customer/CheckoutController.php → uploadProof()`

1. Customer pilih metode pembayaran (QRIS/E-Wallet/Bank Transfer)
2. Customer pilih akun tujuan (dari Data Bank)
3. Customer upload foto bukti transfer
4. `PaymentProof` dibuat
5. Payment status → `menunggu_verifikasi`
6. Notif ke customer: "Bukti Pembayaran Diunggah"

---

### Tahap 3: Admin Verifikasi Pembayaran

**Role:** Admin  
**File:** `app/Http/Controllers/Admin/VerifikasiPembayaranController.php`

**Jika Disetujui (setujui):**
1. `PaymentVerification` dibuat (status: `diterima`)
2. Payment → `terverifikasi`, `dibayar_pada = now()`
3. Checkout → `dibayar`
4. Order → `menunggu_produksi`
5. Notif ke customer: "Pembayaran Diverifikasi"
6. Notif ke Produksi: "Pesanan Siap Diproduksi"

**Jika Ditolak (tolak):**
1. `PaymentVerification` dibuat (status: `ditolak`, + alasan)
2. Payment → `ditolak`
3. Order tetap `pending_payment`
4. Notif ke customer: "Pembayaran Ditolak" (+ alasan)
5. Customer bisa upload ulang bukti

---

### Tahap 4: Admin Proses Pesanan

**Role:** Admin  
**File:** `app/Http/Controllers/Admin/DataPesananController.php → proses()`

1. Admin lihat order `menunggu_produksi` di Data Pesanan
2. Klik "Proses" → modal form muncul
3. Input bahan produksi (pilih dari master atau ketik manual)
4. Input tanggal mulai produksi
5. Input tanggal berakhir produksi
6. Submit → `ProductionOrderBahan` dibuat
7. Order → `diproses` + set `tgl_mulai_produksi`, `tgl_berakhir_produksi`
8. Notif ke customer: "Pesanan Diproses"
9. Notif ke Produksi: "Pesanan Diproses, bahan telah diinput"

---

### Tahap 5: Produksi Accept / Tolak

**Role:** Produksi  
**File:** `app/Http/Controllers/Produksi/DataProduksiController.php`

**Accept:**
1. Produksi lihat order `diproses` di Data Produksi
2. Klik "Accept" → `produksi_dimulai_pada = now()`
3. Notif ke Admin: "Produksi Diterima"
4. Setelah accept: tombol "+ Bahan" dan "Selesai" muncul

**Tolak:**
1. Klik "Tolak" → modal alasan (min 10 karakter)
2. Order → `menunggu_produksi` (balik ke Admin)
3. `produksi_catatan_tolak` disimpan
4. Notif ke Admin: "Produksi Ditolak" (+ alasan)

**Tambah Bahan (opsional):**
1. Setelah Accept, Produksi bisa tambah bahan sendiri
2. Klik "+ Bahan" → modal form muncul
3. Pilih dari master bahan atau ketik manual
4. `ProductionOrderBahan` dibuat (created_by = Produksi)

---

### Tahap 6: Produksi Selesai

**Role:** Produksi  
**File:** `app/Http/Controllers/Produksi/DataProduksiController.php → updateStatus()`

1. Produksi klik "Selesai" → modal form muncul
2. Input jumlah berhasil (required)
3. Input jumlah gagal (optional)
4. Input catatan (optional)
5. Order → `menunggu_qc`
6. `jumlah_berhasil`, `jumlah_gagal` disimpan
7. Notif ke Admin: "Produksi Selesai — Menunggu QC"

---

### Tahap 7: Produksi QC + Packing

**Role:** Produksi  
**File:** `app/Http/Controllers/Produksi/PemeriksaanKualitasController.php → store()`

1. Produksi lihat order `menunggu_qc` di halaman Pemeriksaan Kualitas
2. Klik "QC + Packing" → modal form muncul
3. Lihat hasil produksi (jumlah berhasil/gagal dari Produksi)
4. Input jumlah lulus QC (required)
5. Input jumlah gagal QC (optional)
6. Input catatan QC (optional)
7. `QualityCheck` record dibuat (order_id, checked_by, jumlah_lulus, jumlah_gagal, status, catatan)
8. Order → `siap_kirim` + set `tanggal_qc`, `tanggal_packing`
9. Notif ke Admin: "Produk Siap Dikirim"

---

### Tahap 8: Admin Input Resi

**Role:** Admin  
**File:** `app/Http/Controllers/Admin/PengirimanController.php → simpanResi()`

1. Admin lihat order `siap_kirim` di halaman Pengiriman
2. Pilih kurir
3. Pilih layanan (opsional)
4. Input nomor resi
5. Input estimasi tiba (opsional)
6. `Shipment` dibuat/diupdate (status: `diproses`)

---

### Tahap 9: Admin Kirim

**Role:** Admin  
**File:** `app/Http/Controllers/Admin/PengirimanController.php → kirim()`

1. Admin klik "Kirim"
2. Shipment → `dikirim`, `dikirim_pada = now()`
3. Order → `dikirim`
4. Notif ke Admin: "Pesanan Dikirim"
5. Notif ke Customer: "Pesanan Dikirim" (+ kurir + resi)

---

### Tahap 10: Customer Konfirmasi Terima

**Role:** Customer  
**File:** `app/Http/Controllers/Customer/OrderTrackingController.php → confirm()`

1. Customer lihat order `dikirim` di halaman Order Tracking
2. Klik "Konfirmasi Terima"
3. Order → `selesai`
4. Shipment → `diterima`, `diterima_pada = now()`
5. `WalletService::creditOrder()` → kredit saldo owner
6. Notif ke Owner: "Pesanan Selesai"

---

## Role & Aksi

| Role | Tahap | Aksi |
|------|-------|------|
| **Customer** | 1 | Checkout (pilih produk + input alamat) |
| **Customer** | 2 | Upload bukti pembayaran |
| **Customer** | 10 | Konfirmasi terima pesanan |
| **Admin** | 3 | Verifikasi pembayaran (setujui/tolak) |
| **Admin** | 4 | Proses pesanan (input bahan + tanggal produksi) |
| **Admin** | 8 | Input resi pengiriman |
| **Admin** | 9 | Kirim pesanan |
| **Produksi** | 5 | Accept/tolak produksi |
| **Produksi** | 5 | Tambah bahan sendiri (opsional) |
| **Produksi** | 6 | Selesai produksi (input berhasil/gagal) |
| **Produksi** | 7 | QC + packing (input lulus/gagal) |
| **System** | - | Auto-expire pembayaran (24 jam) |

---

## Path Expired/Batal

```
Payment pending + batas waktu lewat (24 jam)
  ──► Payment: kadaluarsa
       Checkout: kadaluarsa
       Order: dibatalkan
       Notifikasi → Customer: "Pembayaran Kedaluwarsa"
```

Admin juga bisa membatalkan pesanan manual (`batalkan()`) selama status masih:
- `pending_payment`
- `menunggu_produksi`
- `diproses`

Dibutuhkan alasan (min 10 karakter). Notif ke customer: "Pesanan Dibatalkan" (+ alasan).

---

## Bug yang Ditemukan & Diperbaiki

### Bug: `PengirimanController::kirim()` — Order status tidak berubah ke `dikirim`

**File:** `app/Http/Controllers/Admin/PengirimanController.php`  
**Method:** `kirim()`

**Masalah:**
```php
// BUG: cek STATUS_DIPROSES, tapi seharusnya STATUS_SIAP_KIRIM
if ($pesanan->status === Order::STATUS_DIPROSES) {
    $pesanan->update(['status' => Order::STATUS_DIKIRIM]);
}
```

Pada tahap ini, order sudah `siap_kirim` (bukan `diproses` lagi), karena sudah melalui QC. Jadi kondisi `=== STATUS_DIPROSES` akan `false`, dan **order tidak akan berubah ke `dikirim`**. Hanya Shipment yang advance ke `dikirim`.

**Dampak:** Customer tidak bisa konfirmasi terima karena `confirm()` membutuhkan `Order::STATUS_DIKIRIM`.

**Solusi:** Ganti `STATUS_DIPROSES` → `STATUS_SIAP_KIRIM` di `kirim()`.

**Status:** Sudah diperbaiki.
