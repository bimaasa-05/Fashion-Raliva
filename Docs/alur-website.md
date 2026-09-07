# Alur Website RALIVA

Dokumen ini menjelaskan alur website RALIVA dari sisi **role Customer** (belanja & transaksi) beserta kaitannya dengan **role Admin/Gudang/Owner**, berdasarkan implementasi yang sudah tersambung ke database.

---

## 1. Role & Akun Demo

| Role | Email | Password |
| --- | --- | --- |
| Customer | `customer@raliva.test`, `customer2@raliva.test`, `customer3@raliva.test` | `password` |
| Admin | `admin@raliva.test` | — |
| Owner | (2 user) | — |
| Gudang | (3 user) | — |

Catatan: data di database aktif (DB `Raliva_Fashion`) bukan berasal dari `RalivaDemoSeeder`; produk/orders yang ada diberi prefiks nomor `SEED-...`.

---

## 2. Alur Belanja Customer (Halaman sudah dinamis dari DB)

Urutan halaman mengikuti alur belanja, dan **semua data ditampilkan langsung dari database** (tidak ada lagi konten dummy).

1. **Beranda** — `GET /` (route `customer.home`)
2. **Katalog/Detail Produk** — `GET /shop`, `GET /shop/produk/{id}`, `GET /shop/produk/{id}/riviews`
3. **Toko** — `GET /shop/store/{id}`, `/riviews`, `/about` (deskripsi, alamat, kontak, tahun berdiri dari tabel `stores`; `<title>` memakai nama toko/produk)
4. **Pencarian** — `GET /search`
5. **Akun** — `GET/POST /account`, `/account/edit`, `/account/password` (update profil + ganti password, kolom `gender` & `tanggal_lahir`)
6. **Keranjang & Checkout** — `GET /chart`, `GET/POST /checkout`
7. **Pelacakan Pesanan** — `GET /order-tracking` (timeline 5 langkah: Menunggu Pembayaran → Dibayar/Diproses → Dikirim → Selesai; kartu khusus status Dibatalkan; `?order=` untuk memilih pesanan)
8. **Notifikasi** — `GET /notifications` + tandai dibaca (bulk/satuan)
9. **Ulasan** — `GET/POST /reviews` CRUD penuh (create dari item pesanan selesai, edit, hapus; status `DIMODERASI` menunggu moderasi)
10. **Pengaturan** — `GET /settings` (toggle bahasa/dark mode; **Delete Account** `POST /settings/delete-account` — ditolak bila user masih punya pesanan/aktivitas, sukses bila bersih)

---

## 3. Alur Transaksi End-to-End

### 3.1 Checkout (`CheckoutController::store`)
- `POST /checkout` (route `customer.checkout.store`) dipicu tombol **PLACE ORDER** (form berisi `address_id`, `shipping`, `payment_method_id`, opsional `buy`).
- Validasi: metode bayar aktif, alamat milik user wajib ada, item tidak kosong (buy-now 1 varian atau isi keranjang).
- Dalam satu transaksi DB dibuat:
  - **Checkout** (subtotal + total_ongkir + grand_total, status `pending`).
  - **Order per toko** (produk dikelompokkan per `store_id`; nomor `RLV-{storeId}-{6karakter}`; ongkir dibagi proporsional bila lebih dari satu toko; status `pending_payment`).
  - **OrderItem** (snapshot `nama_produk`, `harga_snapshot`, qty, subtotal, total).
  - **Payment** (`status=pending`, `batas_waktu` dari pengaturan metode bayar).
- Keranjang dikosongkan setelah berhasil (baik via keranjang).
- Notifikasi "Pesanan Dibuat" dikirim ke customer.

### 3.2 Pembayaran (`CheckoutController::payment` / `uploadProof`)
- `GET /checkout/{checkout}/payment` menampilkan ringkasan (nomor pesanan, metode bayar, total, batas waktu) + form unggah bukti.
- `POST /checkout/{checkout}/payment` menerima gambar bukti (JPG/PNG, maks 4MB) → dibuat **PaymentProof**, status pembayaran menjadi `menunggu_verifikasi`, notifikasi "Bukti Pembayaran Diunggah".
- Halaman hanya bisa diakses pemilik checkout (404 untuk user lain); upload ulang diperbolehkan saat status `pending` atau `ditolak`.

### 3.3 Verifikasi Admin (loop admin)
- **Data Pembayaran** → admin `setujui` → Payment `terverifikasi`, Checkout `dibayar`, Order `dibayar`, notifikasi customer.
- **Data Pesanan** → admin `proses` → Order `diproses`, notifikasi customer.
- **Pengiriman** → admin `simpanResi` (pilih kurir + nomor resi, estimasi tiba) → Shipment `diproses`; lalu `kirim` → Shipment `dikirim`, Order `dikirim`, notifikasi customer.
- Scope admin dibatasi toko yang ditugaskan (`store_staff` status `aktif`, lihat `App\Support\AdminContext`).

### 3.4 Pelacakan
- `Customer → Order Tracking` membaca Order nyata. Langkah timeline memakai konstanta `OrderTrackingController::STATUS_STEPS/LABELS`:
  `pending_payment=0, dibayar=1, diproses=1, dikirim=3, selesai=4`, `dibatalkan/refund` = khusus (kartu batal).

### 3.5 Catatan Gudang/Warehouse
- Alur penjualan produk tetap ditangani sisi Admin (verifikasi bayar → proses → kirim).
- Flow **Gudang** (`gudang/*`) menangani manajemen stok: barang masuk/keluar, pemindahan, pemeriksaan, stok rusak, riwayat stok, dan konfirmasi request pelanggan (ketersediaan stok) — terpisah dari alur penjualan di atas.

---

## 4. Status yang Digunakan

| Entitas | Status |
| --- | --- |
| Checkout | `pending`, `dibayar`, `kadaluarsa`, `selesai` |
| Order | `pending_payment`, `dibayar`, `diproses`, `dikirim`, `selesai`, `dibatalkan`, `refund` |
| Payment | `pending`, `menunggu_verifikasi`, `terverifikasi`, `ditolak`, `kadaluarsa` |
| Review | `DIMODERASI` (menunggu) → `AKTIF` |
| Notifikasi | tipe `order`, `pembayaran`, `pengiriman`, `komplain`, `wallet`, `promo`, `sistem` |

---

## 5. Konvensi Implementasi

- Halaman customer adalah blade mandiri (tanpa layout bersama) memakai Tailwind CDN + tema material RALIVA; tambahkan `@include('customer._partials.drawer')`.
- Navigasi antar halaman memakai nama route `customer.*`; tombol/aksi dikirim via form POST dengan `@csrf`.
- Pemformatan harga: `Rp {{ number_format($x, 0, ',', '.') }}`.
- Gambar produk: `filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)`, fallback `picsum.photos`.
- Verifikasi CRUD manual dilakukan via `php artisan tinker` dengan `view()->share('errors', new \Illuminate\Support\ViewErrorBag)` sebelum render view.