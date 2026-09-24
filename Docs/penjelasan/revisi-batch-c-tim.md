# Penjelasan Revisi Batch C — Tim, Pelanggan, Pesanan, Iklan

Scope: file Owner (+ konstanta `Role`, notif debit wallet SA).

---

## 1. Karyawan — dari hardcode ID ke konstanta

Role karyawan dipetakan dari `role_id` angka (`3/4/5`) yang rapuh bila seeder
berubah. Sekarang memakai nama role (`Admin/Produksi/Gudang`) via konstanta
`Role::ADMIN/PRODUKSI/GUDANG`, berlaku di controller, view, dan helper
`roleOf()`. Pilihan role tetap hanya 3 itu (owner/superadmin/customer
terproteksi).

Form tambah: email wajib beda (unique + hint), no. telepon opsional, akun
baru langsung `aktif` + verified. Update: resolve `role_id` by nama.

## 2. Data Pelanggan — mahkota & rank global

- Juara 1 podium memakai CSS crown yang selama ini menganggur
  (`.crown-gold/.crown-container/.crown-sparkle` + ikon `crown`); badge tabel
  leader ikut ikon mahkota.
- Rank tidak lagi reset per halaman (`offset halaman + i + 1`); summary
  repeat & rata-rata dihitung global, bukan halaman aktif.
- Label hardcode diperbaiki: badge header = total asli, bulan = bulan berjalan.

## 3. Detail pesanan Owner — relasi rusak + data lengkap

Empat relasi salah yang selalu tampil `-` diperbaiki: metode via
`checkout.payment.paymentMethod`, produk via `productVariant`, harga via
`harga_snapshot`. Eager controller ditambah (payment proofs/verifications,
shipments + kurir/layanan).

Detail kini memuat: alamat penerima + telepon, tipe online/offline, catatan,
kurir + resi + status kirim, status bayar + tombol bukti bayar.
Tombol close yang memakai `data-drawer-close` diganti `data-modal-close`.

## 4. Notifikasi pengajuan iklan

- Owner ajukan: `ActivityLogger` + `fireSelf` (sebelumnya hanya notif SA).
- SA setujui: notif debit wallet terpisah (`Saldo terdebit Rp X untuk
  biaya iklan`) selain notif persetujuan.

## Batasan jujur

1. Karyawan nonaktif tetap bisa login (user tidak dihapus) — by design aman.
2. Produksi/Gudang yang tak verifikasi pembayaran selalu rekap 0 — wajar
   karena atribusi berbasis verifier.
