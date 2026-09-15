# Data Bank sebagai Satu-satunya Sumber Data Payment

> Status: disetujui user (14 Sep 2026). Keputusan: **migrasi in-place** (tanpa hapus data) +
> **3 tab di halaman Data Bank SuperAdmin** (Bank Transfer / E-Wallet / QRIS).

## 1. Latar belakang

- Halaman **Data Bank SuperAdmin** (`banks` + `platform_bank_accounts`) hanya untuk rekening
  platform; 4 dari 5 bank hasil seed tampil "Belum ada rekening platform"
  karena `platform_bank_accounts` cuma punya 1 baris sisa.
- Opsi bayar di checkout (QRIS, DANA/GoPay/OVO/ShopeePay/LinkAja/Jenius,
  transfer 5 bank) hidup di tabel terpisah `payment_method_accounts`,
  **tidak terhubung** ke Data Bank dan tidak punya halaman CRUD di SuperAdmin
  (halaman Data Pembayaran SuperAdmin hanya daftar transaksi).

## 2. Keputusan desain

1. `platform_bank_accounts` jadi **satu-satunya master akun payment** dan tetap
   jadi master rekening platform (pencairan dana, rekening tujuan iklan).
2. Tabel `payment_method_accounts` + model `PaymentMethodAccount` +
   `PaymentMethodAccountSeeder` **dihapus** (diganti migrasi data in-place).
3. `payment_methods` (meta metode: nama, kode, batas waktu, status) **tetap**.
4. Kolom baru di `platform_bank_accounts`: `jenis` (`qris` | `ewallet` |
   `bank_transfer` — sama persis dengan `payment_methods.kode_metode`),
   `nama`, `kode`, `deskripsi`, `file_gambar`, `urutan`; `bank_id` jadi nullable
   (hanya diisi untuk `bank_transfer`).
5. `payments.payment_method_account_id` di-rename jadi `payment_account_id`,
   FK ulang ke `platform_bank_accounts`; relasi `Payment::account()` tetap
   bernama sama. `PaymentMethod::accounts()` jadi
   `hasMany(PlatformBankAccount, 'jenis', 'kode_metode')`.
6. Halaman Data Bank SuperAdmin jadi **3 tab**: Bank Transfer (grid lama,
   tidak berubah perilaku), E-Wallet (CRUD penuh), QRIS (editor akun tunggal).
7. Dropdown rekening tujuan iklan (Owner & SuperAdmin `PeringkatIklan`)
   difilter `bank_id NOT NULL` agar e-wallet/QRIS tidak bocor ke sana
   (perilaku lama = khusus transfer bank).

## 3. Lingkup file

- Migrasi baru:
  - `2026_09_14_000001_extend_platform_bank_accounts_for_payment.php`
  - `2026_09_14_000002_migrate_payment_method_accounts_into_platform_bank_accounts.php`
    (salin 12 akun, `updateOrCreate` per bank, remap `payments`, rename kolom,
    drop tabel lama)
- Model: `PlatformBankAccount.php`, `PaymentMethod.php`, `Payment.php`;
  hapus `PaymentMethodAccount.php`.
- Seeder: `PlatformPaymentAccountSeeder.php` (baru, idempotent); hapus
  `PaymentMethodAccountSeeder.php`; update `DatabaseSeeder.php`.
- Controller: `Customer/CheckoutController.php` (validasi + lookup akun),
  `SuperAdmin/DataBankController.php` (tab + CRUD akun),
  `Owner/PeringkatIklanController.php` + `SuperAdmin/PeringkatIklanController.php`
  (filter bank_id).
- View: `SuperAdmin/data-bank/index.blade.php` (3 tab + modal),
  `customer/checkout/payment.blade.php` (nama input `payment_account_id`).
- Route: `superadmin.data-bank.account.store/update/delete` di `routes/web.php`.

## 4. Perilaku tiap role (ringkas)

- **SuperAdmin**: kelola bank + rekening transfer (tab 1), e-wallet (tab 2),
  QRIS/gambar (tab 3). Data Bank tetap sumber rekening pencairan & iklan.
- **Customer**: opsi bayar di checkout dibaca dari Data Bank (tidak ada
  perubahan alur, hanya sumber data).
- **Admin**: verifikasi pembayaran tampil akun seperti biasa.
- **Owner**: order-tracking & daftar rekening iklan tidak berubah
  (iklan tetap khusus transfer bank).

## 5. Verifikasi

`php -l` → `php artisan migrate` (in-place) → `view:clear` + `view:cache` →
render headless: Data Bank 3 tab (5 bank ber-rekening, 6 e-wallet, QRIS);
checkout tampil 12 opsi & `uploadProof` tersimpan; payment lama ter-resolve;
dropdown iklan hanya bank. Skrip temp dihapus, data test di-restore.
