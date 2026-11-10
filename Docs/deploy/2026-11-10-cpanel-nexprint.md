# Deploy Production cPanel — NEXPRINT

Tanggal: 2026-11-10
Target: `https://nexprint.my.id`
Paket: `raliva-nexprint-production-2026-11-10.zip`

## Isi paket

Paket berisi kode aplikasi, `vendor` production (`--no-dev`), `public/build`
hasil Vite, aset `public/assets`, migration, dan seeder dasar. Paket **tidak**
berisi:

- `.env`
- `.git`, `node_modules`, `tests`, `Docs`
- file storage lokal (logo/produk/dokumen demo)
- `database-RalivaFashion.sql`
- seeder demo (`DatabaseSeeder`, `RalivaDemoSeeder`, `OwnerSeeder`,
  `SuperAdminSeeder`, `AdminDemoSeeder`, `GudangDemoSeeder`, dan seeder demo
  lain)
- `public/storage` (dibuat ulang dengan `php artisan storage:link`)

## 0. Sebelum upload

- Backup database dan file production yang lama.
- Pastikan PHP minimal 8.2 dengan ekstensi: `bcmath`, `ctype`, `curl`, `dom`,
  `fileinfo`, `gd`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`,
  `zip`.
- Pastikan MySQL/MariaDB dan SSL `https://nexprint.my.id` aktif.
- Siapkan database + user MySQL baru di cPanel dan catat kredensialnya.

## 1. Upload dan extract

1. Upload zip ke home directory cPanel (bukan langsung ke `public_html`).
2. Extract zip.
3. Arahkan document root domain/subdomain ke folder `public/` aplikasi.
   Jika hosting tidak mengizinkan ubah document root, pindahkan isi `public/`
   ke `public_html/` lalu sesuaikan path di `public_html/index.php` agar
   menunjuk ke folder aplikasi. Cara document root langsung lebih disarankan.
4. Pastikan folder `storage/` dan `bootstrap/cache/` dapat ditulis web server
   (umumnya `755` atau `775`, tergantung hosting).

## 2. Database

### Jika database production sudah berisi data / import dump

```bash
php artisan migrate --force
```

Jangan jalankan `migrate:fresh`.

### Jika database benar-benar kosong

`php artisan migrate --force` **tidak** mengisi role, permission, kategori,
kurir, kota, dan konten dasar. Minimal jalankan:

```bash
php artisan migrate --force

php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=PermissionSeeder --force
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=CourierSeeder --force
php artisan db:seed --class=CitySeeder --force
php artisan db:seed --class=CategorySeeder --force
php artisan db:seed --class=StoreCategorySeeder --force
php artisan db:seed --class=StoreCategorySizeSeeder --force
php artisan db:seed --class=HelpCenterSeeder --force
php artisan db:seed --class=LegalDocumentSeeder --force
php artisan db:seed --class=DataBankSeeder --force
php artisan db:seed --class=WarehouseStaffPermissionSeeder --force
```

**Jangan** menjalankan `DatabaseSeeder`, `RalivaDemoSeeder`, `OwnerSeeder`,
`SuperAdminSeeder`, atau seeder demo lain di production.

`DataBankSeeder` mengisi rekening platform contoh. Setelah login SuperAdmin,
ganti nomor rekening/QRIS contoh dengan data rekening production yang benar
sebelum menerima pembayaran.

### Membuat SuperAdmin production

Jangan memakai password demo `password`. Buat akun dengan password kuat:

```bash
php artisan tinker
```

```php
$role = App\Models\Role::where('nama_role', 'Super Admin')->value('role_id');

App\Models\User::updateOrCreate(
    ['email' => 'admin@nexprint.my.id'],
    [
        'nama_lengkap' => 'Super Admin NEXPRINT',
        'password' => Illuminate\Support\Facades\Hash::make('GANTI_DENGAN_PASSWORD_KUAT'),
        'role_id' => $role,
        'nomor_telepon' => '08xxxxxxxxxx',
        'email_verified_at' => now(),
        'status' => 'aktif',
    ]
);
```

## 3. File `.env`

Salin `.env.example` menjadi `.env`, lalu isi minimal:

```dotenv
APP_NAME=Raliva_Fashion
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://nexprint.my.id
APP_LOCALE=id
APP_FALLBACK_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_cpanel
DB_USERNAME=user_database_cpanel
DB_PASSWORD=password_database_cpanel

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

LOG_CHANNEL=stack
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.provider.com
MAIL_PORT=465
MAIL_USERNAME=email@nexprint.my.id
MAIL_PASSWORD=password_email
MAIL_FROM_ADDRESS=email@nexprint.my.id
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_CLIENT_ID=isi_dari_google_cloud
GOOGLE_CLIENT_SECRET=isi_dari_google_cloud
GOOGLE_REDIRECT_URI=https://nexprint.my.id/auth/google/callback
```

Generate `APP_KEY`:

```bash
php artisan key:generate --force
```

Jangan pernah commit `.env` atau mengirim isinya ke repository.

## 4. Perintah Artisan

Jalankan lewat cPanel Terminal/SSH:

```bash
php artisan optimize:clear
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika `config:cache` gagal, cek `.env` dan pastikan tidak ada karakter aneh.
Setelah selesai, cek:

```bash
php artisan about
```

## 5. Google OAuth dan email

- Google Cloud Console → Authorized redirect URI:
  `https://nexprint.my.id/auth/google/callback`
- `APP_URL` dan `GOOGLE_REDIRECT_URI` harus memakai origin yang sama.
- SMTP port 465 memakai `MAIL_SCHEME=smtps`.
- Uji lupa password/OTP setelah SMTP aktif.

## 6. Smoke test

- `https://nexprint.my.id/` → 200 dan brand `NEXPRINT`.
- `https://nexprint.my.id/login` → 200.
- Login SuperAdmin → dashboard tampil, sidebar default terbuka.
- `/customer/shop` → 200, halaman toko tampil.
- Upload foto produk → URL `/storage/...` dapat diakses.
- Cek aset build: `/build/assets/app-*.css` dan `.js` → 200.
- Cek `storage/logs/laravel.log` tidak berisi error 500.
- Cek tidak ada menu/halaman notifikasi customer.

## 7. Rollback

- Simpan backup file dan database sebelum deploy.
- Jika deploy gagal, restore file + database, lalu:
  ```bash
  php artisan optimize:clear
  ```
- Jangan hapus backup sebelum smoke test selesai.
