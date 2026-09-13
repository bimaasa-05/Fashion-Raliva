# Rencana — Guest Checkout 3-Langkah + Cek Resi + My Account Teaser

> Status: **disetujui user** — eksekusi berurutan 1 → 9. Setiap langkah selesai + diverifikasi.
> Keputusan terkunci:
> - Tamu beli lewat tombol **BUY** (`checkout?buy={variantId}`), **TANPA keranjang** (keranjang tetap member). Tombol CART tamu → login.
> - Cek resi: **nomor resi** ATAU **nomor order + nomor telepon**.
> - Email Data Pemesan **wajib** (dipakai auto-login sebagai username akun).
> - Setelah "Lanjut Ke Pembayaran" → **auto-register + auto-login**, password default **Raliva123** (ditampilkan di layar Bayar).
> - Ongkir (Regular/Express) tetap dipilih di langkah Review (masuk Rincian Harga); method pembayaran pindah ke langkah Bayar.
> - Layout layar Bayar: **grid 2 kolom** (form bayar | Rincian Pembayaran).

---

## 1. Migrasi — `checkouts / orders / payments` guest-friendly

| # | Perubahan | Detail |
|---|---|---|
| 1a | `checkouts.user_id` → nullable | Drop FK `restrict`, ubah kolom nullable (fallback pesanan tamu sebelum akun jadi). |
| 1b | `checkouts` snapshot alamat | Tambah kolom nullable: `email_pelanggan`, `nama_penerima`, `nomor_telepon`, `alamat`, `kota`, `provinsi`, `kode_pos`. Diisi otomatis dari `addresses` default bila member; tamu isi form Data Pemesan. |
| 1c | `orders.catatan` | `text` nullable — **Catatan Opsional** (instruksi pengiriman untuk produk). Terpisah dari Data Pemesan. Satu catatan per checkout → sama untuk semua `orders` (per toko) yang lahir dari checkout tsb. |
| 1d | `payments.payment_method_id` → nullable | Method dipilih di langkah Bayar, bukan di Review. Dibuat bersama `Payment` di `store()` atau diisi saat `uploadProof()`. `down()` kebalikan penuh. |

**File migrasi:** `2026_09_13_000001_add_guest_checkout_fields_to_checkouts_orders_payments.php`

---

## 2. Layar 1 — Review (`customer/checkout/index`)

Restrukturisasi kartu utama:

- **Data Pemesan** (form — terpisah, paling atas): Nama Lengkap*, No. Telepon/WA*, Email* (wajib — dipakai akun otomatis), Alamat Sementara* (alamat, kota, provinsi, kode pos). Prefill dari `addresses` default bila member; kosong bila tamu.
- **Catatan Opsional** (kartu/section terpisah, bukan field Data Pemesan): textarea, placeholder *"cth. 1–2 produk dikirim duluan ke alamat ini"* — disimpan ke `orders.catatan`.
- **Rincian Pesanan**: daftar produk yang dibeli (`?buy=` untuk tamu; keranjang untuk member) — foto + varian + qty + harga.
- **Rincian Harga** + **pilihan ongkir**: radio Regular/Express → Subtotal, Shipping, Tax, Total + tombol **"Lanjut Ke Pembayaran"** (sticky bottom bar).
- Method pembayaran **dihapus** dari halaman ini (pindah ke langkah 2).

**File:** `resources/views/customer/checkout/index.blade.php`

---

## 3. Controller — `CheckoutController` guest-aware

| # | Logic |
|---|---|
| 3a | `index()` — tamu: tanpa `Cart::firstOrCreate`; tanpa `?buy` → redirect shop + toast. Role non-Customer yang login (Owner/Admin/Gudang) → 403. |
| 3b | `store()` — validasi: `nama_lengkap` required, `nomor_telepon` required, `email` `required\|email\|max:150`, alamat fields required, `catatan` nullable max 1000, `shipping` `required\|in:0,35000`, `buy` nullable. **Auto-register**: `User::updateOrCreate` atau `firstOr` + cek duplikat: bila email sudah ada → coba `Auth::attempt(email, 'Raliva123')`; bila gagal → kembali Review, toast *"Email sudah terdaftar — silakan Masuk lalu checkout"* + tautan login `?redirect=/customer/checkout?buy=...`. Bila baru → `Hash::make('Raliva123')`, `role_id` Customer, `nomor_telepon`, `status` aktif → `Auth::login($user)` + `regenerate()`. Buat `Checkout` (snapshot + `user_id`), `Order` per toko (+ `catatan`), `OrderItem`s, `Payment` (method `null`, `pending`, `batas_waktu` = now+1440m). Notif personal hanya bila member; owner tetap dapat notif pesanan baru. |
| 3c | `payment()` — find checkout where: member `user_id == Auth::id()`; tamu fallback baru-login → sama user_id; bila checkout tidak ditemukan → 404. Tampilkan banner akun bila baru dibuat. |
| 3d | `uploadProof()` — validasi sekarang: `payment_method_id` **required\|exists** + `bukti` image. Set `Payment.payment_method_id` bila masih null, buat `PaymentProof`, update status `menunggu_verifikasi`. Redirect → **Selesai** (akun baru, flash) / `order-tracking` (member lama). |
| 3e | `resolveItems()` — untuk `buy` tanpa cart tetap sama; untuk keranjang member tetap sama. |

**File:** `app/Http/Controllers/Customer/CheckoutController.php`, `app/Models/{Checkout,Order,Payment}` fillable.

---

## 4. Layar 2 — Bayar (`customer/checkout/payment`)

- **Banner hijau** paling atas (hanya bila flash `akun_baru`): *"Akun berhasil dibuat — Email: {email} • Password: Raliva123 (ubah di My Account → Ganti Password)"* + link ke `account.password`.
- **Grid 2 kolom** (desktop `md:grid-cols-[1.7fr_1fr]`; mobile 1 kolom stack):
  - Kolom kiri: **Pilih Metode Pembayaran** (grid tombol) + **Upload Bukti** (dropzone).
  - Kolom kanan: **Rincian Pembayaran** (nomor order(s), metode terpilih, total dibayar, batas waktu, status) — kartu premium, aksen burgundy.
- Kartu detail lama dipertahankan. `batas_waktu` format `d M Y, H:i`.

**File:** `resources/views/customer/checkout/payment.blade.php`

---

## 5. Layar 3 — Selesai

- Route baru: `GET /customer/checkout/{checkout}/selesai` → `customer.checkout.selesai` (guard `user_id`).
- View baru: `customer/checkout/selesai.blade.php` — centang sukses, *"Pesanan berhasil!"*, daftar nomor order, total, tips ganti password `Raliva123`, CTA **"Lacak via Resi"** (`→ customer.cek-resi`) + **"Lanjut Belanja"**.
- Setelah `uploadProof()` untuk pengguna baru → redirect `checkout.selesai` (flash akun); pengguna lama → `order-tracking` seperti sekarang (tetap konsisten — atau bisa diarahkan ke `selesai` juga).

**File baru:** `resources/views/customer/checkout/selesai.blade.php`

---

## 6. Cek Resi publik (`customer/cek-resi`)

- **Controller baru:** `App\Http\Controllers\Customer\CekResiController`
  - `index()` — GET form (guest-safe, tanpa middleware auth/role).
  - `search(Request)` — POST: validasi `nomor_resi` nullable, `nomor_order` + `nomor_telepon` nullable; cari I) `Shipment.nomor_resi = input` (with `courier`, `shippingService`, `order.checkout`, `order.store`, `order.items.productVariant.product.images`); II) `orders.nomor_order = input` + `checkout.nomor_telepon || checkout.email_pelanggan || user.nomor_telepon` cocok. Tampilkan: timeline `pending→diproses→dikirim→diterima` (aktif sesuai `shipment.status` / `order.status`), ekspedisi + nomor resi, estimasi, `dikirim_pada`/`diterima_pada`, info order (nomor, toko, item, total), status badge.
- **View baru:** `resources/views/customer/cek-resi/index.blade.php` (standalone, drawer + bottom-nav + reveal-up + card-premium).
- **Tambah tampilan nomor resi + ekspedisi** di `customer/order-tracking/index.blade.php` (saat ini tidak tampil).

**File:** `app/Http/Controllers/Customer/CekResiController.php`, `resources/views/customer/cek-resi/index.blade.php`

---

## 7. My Account teaser (guest)

- **Controller baru / reuse:** `App\Http\Controllers\Customer\AccountController@index` — guest: `view('customer.account.guest')`; member: `view('customer.account.index')`; role lain: 403. `account.edit`/`password`/`reviews`/`address.*`/`settings` tetap dalam `role:Customer`.
- **Route:** `GET /customer/account` dikeluarkan dari group `role:Customer` → `account` (publik, branching). `POST /account` + `account/edit|password` + `settings|address|reviews|wishlist` tetap dalam group `role:Customer`.
- **View baru:** `resources/views/customer/account/guest.blade.php` — hero *"Akun Raliva"*, list isi akun (Pesanan & Lacak Resi, Alamat, Wishlist, Ulasan, Notifikasi, Pengaturan), CTA **"Masuk / Daftar"** (`route('login', ['redirect'=>route('customer.account')])` + `register`) + **"Lanjut Belanja"**.
- **Navigasi:** `customer/_partials/bottom-nav` & `customer/_partials/drawer` — guest: "My Account" → `customer.account` (teaser), "Pesanan" → `customer.cek-resi`; member tetap lama.

**File:** `app/Http/Controllers/Customer/AccountController.php`, `resources/views/customer/account/guest.blade.php`

---

## 8. Perubahan Route & Navigasi (ringkasan)

| Route | Dari | Ke |
|---|---|---|
| `GET /customer/checkout` | `role:Customer` | publik (guest + member, branch) |
| `POST /customer/checkout` | `role:Customer` | publik (guest store membuat akun) |
| `GET /customer/checkout/{c}/payment` | `role:Customer` | publik (verify `user_id`) |
| `POST /customer/checkout/{c}/payment` | `role:Customer` | publik (verify `user_id`) |
| `GET /customer/checkout/{c}/selesai` | — | **baru**, auth+Customer |
| `GET /customer/cek-resi` | — | **baru**, publik |
| `POST /customer/cek-resi` | — | **baru**, publik |
| `GET /customer/account` | `role:Customer` (closure) | **publik** `AccountController@index` (branch) |
| `GET /customer/account/edit` & `password` & `POST /account` | `role:Customer` | tetap `role:Customer` |
| `GET /customer/order-tracking` | `role:Customer` | tetap `role:Customer` (member); guest diarah cek-resi |

---

## 9. Admin & Owner null-safe

- Fallback `checkout.nama_penerima` / `checkout.nomor_telepon` di `DataPesananController`, `VerifikasiPembayaranController`, `PengirimanController`, `KoordinasiGudangController`, `DashboardOperasionalController` + blade mereka saat `checkout.user` null.
- Lewati notifikasi per-user bila `checkout.user_id` null.
- `CartController::add` + `customer/_partials/cart-script` — guest klik CART → JSON error ramah: *"Masuk untuk memakai keranjang, atau klik Beli Sekarang"* (toast).

---

## 10. Verifikasi

- `php -l` semua file baru/ubah.
- `php artisan migrate`  &  `php artisan migrate:fresh --seed` (semua seeder hijau), `php artisan view:cache`.
- Uji headless (port 8000): tamu `BUY → Review (isi Data Pemesan + Catatan) → Lanjut Ke Pembayaran → banner akun (email+Raliva123) → pilih metode + upload bukti → Selesai → admin set resi → cek-resi tampil status`. Alur member lama utuh.

---

### Catatan file implementasi utama

`routes/web.php`, `app/Http/Controllers/Customer/{CheckoutController, AccountController, CekResiController}`, `app/Models/{Checkout, Order, Payment}`, `resources/views/customer/checkout/{index,payment,selesai}`, `resources/views/customer/cek-resi/index`, `resources/views/customer/account/guest`, `resources/views/customer/_partials/{bottom-nav,drawer}`, `resources/views/customer/order-tracking/index`, migrasi guest-checkout, beberapa controller/blade Admin.
