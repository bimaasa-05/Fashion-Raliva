# Rencana Batch Berikut — Logic Pembayaran (5 Area)

> Status: **disetujui user** — urutan eksekusi A → B → C → D → E.
> Konteks: lanjutan fitur detail metode pembayaran (QRIS / E-Wallet / Bank Transfer + akun + upload 2-fase) yang sudah selesai.
> Keputusan terkunci user untuk **jauh ke depan**: refund TIDAK kembali ke metode pembayaran asal, melainkan ke **saldo akun customer** (wadah: tabel `wallet` + `WalletService` yang sudah ada); metode pembayaran bisa ditambah opsi **"Saldo"** (lihat Roadmap 6).

---

## A. Auto-Expired Pembayaran (prioritas utama)

**Masalah:** `payments.batas_waktu`, `Payment::STATUS_KADALUARSA` (`kadaluarsa`), dan `Checkout::STATUS_KADALUARSA` sudah ada tapi **tidak pernah dipakai**. Order `pending_payment` yang tak pernah dibayar menggantung selamanya. Stok TIDAK dikurangi saat pesanan dibuat (hanya di gudang ops) → **tidak perlu restore stok**.

| # | Yang dibuat | Logic |
|---|---|---|
| A1 | `app/Support/PaymentExpiry.php` — service idempoten `expireOverdue(): int` | Query `Payment::where(status=pending)->whereNotNull(batas_waktu)->where(batas_waktu < now())` yang masih punya order `pending_payment`. Per payment dalam transaksi: `payment.status → kadaluarsa`, `checkout.status → kadaluarsa`, semua order `pending_payment → dibatalkan` (`Order::STATUS_DIBATALKAN`), kirim `Notification` (TIPE_PEMBAYARAN) ke customer: "Pembayaran kedaluwarsa — pesanan dibatalkan". |
| A2 | `app/Console/Commands/ExpirePendingPayments.php` → `php artisan payment:expire` | Pembungkus command memanggil service. |
| A3 | Jadwal + lazy fallback | `routes/console.php`: `Schedule::command('payment:expire')->everyMinute()->withoutOverlapping()`; panggil `PaymentExpiry::expireOverdue()` di `OrderTrackingController::index()` & `CheckoutController::payment()` (idempotent → aman dijalankan berulang, jadi tanpa cron pun tetap berfungsi). |

**UI existing tanpa perubahan:** halaman Pesanan order `dibatalkan` tanpa tombol primer; label "Dibatalkan" di order-tracking. SuperAdmin `DataPembayaranController` sudah menghitung tab `kadaluarsa`.

**Verifikasi:** set `batas_waktu` masa lalu pada payment test (mis. ck35) → jalankan command → cek status payment/checkout/order berubah + notifikasi terkirim (via tinker + UI).

---

## B. Order-Tracking Tampilkan Akun Pembayaran

**Masalah:** customer di halaman lacak pesanan tidak bisa melihat ke mana/akun apa dia membayar (hanya ada CTA "Lanjutkan Pembayaran").

| # | Yang dibuat | Logic |
|---|---|---|
| B1 | `OrderTrackingController.php` line 49 | Eager-load `'checkout.payment.paymentMethod'` + `'checkout.payment.account'` (ganti `'checkout.payment'`). |
| B2 | `order-tracking/index.blade.php` — blok baru "Rincian Pembayaran" di bawah header | Tampilkan bila `$selected->checkout?->payment` ada: nama metode, akun (logo + nama + nomor_rekening + nama_pemilik), total, status pembayaran, dan `batas_waktu` bila masih `pending`. |

**Verifikasi:** headless order 35 (pending, belum pilih metode) & order terverifikasi yang punya akun.

---

## C. Halaman Selesai Checkout Tampilkan Akun

| # | Yang dibuat | Logic |
|---|---|---|
| C1 | `CheckoutController::selesai()` (~line 391) | Tambah `'payment.account'` di eager-load. |
| C2 | `checkout/selesai.blade.php` — kartu "DETAIL PESANAN" (~line 96) | Baris "Metode Pembayaran" + sub-baris akun (mis. `QRIS RALIVA • atas nama RALIVA Fashion`) + keterangan "Bukti telah diunggah". |

**Verifikasi:** render tinker step-3 checkout ber-akun.

---

## D. Admin Verifikasi Pembayaran: Logo Akun

| # | Yang dibuat | Logic |
|---|---|---|
| D1 | `verifikasi-pembayaran/index.blade.php` — badge metode (line 73) | Sisipkan `<img>` logo kecil bila `account->file_gambar` ada (dipangkas, object-contain). |
| D2 | Detail modal (sekitar line 172) | Blok baru "Tujuan Pembayaran": logo, nama akun, nomor_rekening, nama_pemilik, nama metode — pembanding bukti transfer. |

**Status:** eager-load `account` di `VerifikasiPembayaranController` sudah ada.

**Verifikasi:** tinker render kartu + modal dengan data ber-akun (badge "QRIS • QRIS RALIVA" + logo tampil).

---

## E. Laporan per Metode Pembayaran

| # | Yang dibuat | Logic |
|---|---|---|
| E1 | `LaporanController::index` | `$perMetode`: `Payment::where(status=terverifikasi)` yang checkout-nnya punya order di store scope, group by `payment_method_id` → nama metode, jumlah transaksi, total nominal. |
| E2 | `Admin/laporan/index.blade.php` | Seksi baru "Pendapatan per Metode Pembayaran" (tabel). |

**Verifikasi:** render laporan + cek agregasi via tinker.

---

## Verifikasi Umum per Area

`php -l` → `php artisan view:cache` → render tinker halaman terkait → uji headless (login admin/admin-owner/customer) → cek status & notifikasi di DB.

---

## Roadmap 6 — (SCOPE BERIKUTNYA, belum dieksekusi) Customer Wallet / Saldo ala Beautycare

> Disepakati user sebagai arah pengembangan selanjutnya (di luar batch ini).

- **Refund → saldo**: pengembalian dana dikreditkan ke **saldo akun customer** (`wallet`) BUKAN balik ke metode pembayaran asal.
- **Metode pembayaran "Saldo"**: `payment_methods` + `payment_method_accounts` bisa menambah opsi "Saldo" sebagai metode pembayaran.
- **Tarik saldo customer** (gjika perlu): perluasan `Withdrawal` + `PermintaanPenarikanController`.
- Fondasi siap: tabel `wallet` (saldo_tersedia/saldo_tertahan), `WalletService` (creditOrder dll), superadmin penarikan sudah jalan untuk owner/komisi.

---

*Disusun: 14 September 2026 — dari kesepakatan pilihan logic oleh user (A, B, C, D, E) + roadmap saldo.*