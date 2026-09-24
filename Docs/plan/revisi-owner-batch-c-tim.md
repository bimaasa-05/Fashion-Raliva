# Revisi Owner Batch C — Tim, Pelanggan, Pesanan, Iklan

Scope: file Owner (+ konstanta Role). Tanpa ubah alur Customer/SuperAdmin.

## C1 — Karyawan hardening

- [ ] Ganti hardcode ID `3/4/5` → konstanta `Role::ADMIN/PRODUKSI/GUDANG`
      (controller + view + `StoreStaffController` terkait).
- [ ] Validasi email unique + no. telepon; proteksi role
      customer/owner/superadmin tidak bisa jadi `StoreStaff`.
- [ ] Perbaiki relasi copy-paste `StoreStaff::warehouseStaffPermissions()`.

## C2 — Mahkota Data Pelanggan

- [ ] Pakai CSS crown yang menganggur untuk juara 1 + badge leader.
- [ ] Rank global (tidak reset per halaman); perbaiki label hardcode
      (`Pelanggan Baru (Agu)`, `1.284`, footer bulanan); pakai `$topLeader`.

## C3 — Detail pesanan Owner

- [ ] Perbaiki 4 relasi rusak: `checkout.payment.paymentMethod`,
      `productVariant` (bukan `variant`), `paymentMethod->nama_metode`.
- [ ] Tambah: alamat penerima, kurir/resi/ongkir (`shipments`),
      status + bukti pembayaran, catatan, tipe pesanan.
- [ ] Perbaiki tombol close (`data-modal-close`, bukan drawer).

## C4 — Notifikasi pengajuan iklan

- [ ] `fireSelf` + `ActivityLogger` saat Owner ajukan.
- [ ] Notif debit wallet terpisah saat SA setujui.

## Verifikasi

- [ ] `php -l` + `view:cache`.
- [ ] Manual: tambah/edit karyawan tiap role; podium mahkota; buka detail
      tiap status pesanan; ajukan iklan → notif masuk.
