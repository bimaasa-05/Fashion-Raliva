# Rencana Batch 8: QC + Packing → Siap Kirim → Dikirim

> Dibuat: 2026-09-15  
> Status: Planning — belum dieksekusi  
> Eksekusi: Setelah plan ditulis, langsung eksekusi

---

## Daftar Isi

1. [Flow Final](#flow-final)
2. [Keputusan User](#keputusan-user)
3. [Temuan Teknis](#temuan-teknis)
4. [Detail Perubahan](#detail-perubahan)
5. [File Terpengaruh Total](#file-terpengaruh-total)
6. [Checklist Verifikasi](#checklist-verifikasi)

---

## Flow Final

```
pending_payment → menunggu_produksi → diproses → menunggu_qc → siap_kirim → dikirim → selesai
                                                ↑ Produksi   ↑ Produksi    ↑ Admin    ↑ Customer
```

### Tahapan

| Tahap | Role | Aksi | Status Sebelum | Status Sesudah |
|-------|------|------|----------------|----------------|
| Selesai Produksi | Produksi | Input jumlah berhasil + gagal | `diproses` | `menunggu_qc` |
| QC + Packing | Produksi | Input lulus/gagal + packing selesai | `menunggu_qc` | `siap_kirim` |
| Kirim | Admin | Input resi + kirim | `siap_kirim` | `dikirim` |
| Terima | Customer | Konfirmasi terima | `dikirim` | `selesai` |

---

## Keputusan User

1. **QC + Packing = 1 langkah** — Produksi input hasil QC + langsung packing → status jadi `siap_kirim`
2. **QC oleh Produksi** — tim yang sama, tidak perlu role terpisah
3. **Tulis plan + eksekusi** — tulis dokumen plan ke `Docs/plan/`, lalu langsung eksekusi

---

## Temuan Teknis

### QualityCheck Model (sudah ada!)

- **File:** `app/Models/QualityCheck.php`
- **PK:** `quality_check_id`
- **Fillable:** `production_order_id`, `checked_by`, `jumlah_lulus`, `jumlah_gagal`, `status` (lulus/gagal/sebagian), `catatan`, `diperiksa_pada`
- **Casts:** `diperiksa_pada => datetime`
- **Relasi:** `productionOrder()` → `ProductionOrder`, `checker()` → `User`
- **Masalah:** `quality_checks` terlinkung ke `production_order_id` (tabel `production_orders`), BUKAN ke `order_id` (tabel `orders`)
- **Solusi:** Tambah kolom `order_id` nullable di `quality_checks` + relasi `order()` di model

### Tabel `quality_checks` schema (dari migration `2026_08_21_000150`)

```sql
quality_check_id      BIGINT PK
production_order_id   BIGINT FK → production_orders
checked_by           BIGINT FK → users
jumlah_lulus         UNSIGNED INT
jumlah_gagal         UNSIGNED INT DEFAULT 0
status               VARCHAR(20)  -- lulus/gagal/sebagian
catatan              TEXT NULL
diperiksa_pada       DATETIME
timestamps
```

### Admin PengirimanController (sudah ada!)

- **File:** `app/Http/Controllers/Admin/PengirimanController.php`
- **index():** query order `STATUS_DIPROSES` yang tidak punya shipments → "Siap Kirim" list
- **simpanResi():** guard `status === STATUS_DIPROSES` → simpan resi → update shipment status → order `STATUS_DIKIRIM`
- **Perlu ubah:** ganti `STATUS_DIPROSES` → `STATUS_SIAP_KIRIM` di kedua method
- **View:** `Admin/pengiriman/index.blade.php` sudah ada dengan form resi per order — tidak perlu banyak perubahan

### Produksi PemeriksaanKualitasController (stub!)

- **File:** `app/Http/Controllers/Produksi/PemeriksaanKualitasController.php`
- Hanya `return view(...)` — tidak ada data, tidak ada POST route
- Perlu implement: `index()` + `store()`

### Produksi ProdukSelesaiController (stub!)

- **File:** `app/Http/Controllers/Produksi/ProdukSelesaiController.php`
- Hanya `return view(...)` — tidak ada data
- Perlu implement: `index()` untuk list `siap_kirim`

### Order Model

- **File:** `app/Models/Order.php`
- Status yang sudah ada: `pending_payment`, `menunggu_produksi`, `diproses`, `dikirim`, `selesai`, `dibatalkan`, `refund`
- **Perlu tambah:** `STATUS_MENUNGGU_QC = 'menunggu_qc'`, `STATUS_SIAP_KIRIM = 'siap_kirim'`
- `orders.status` adalah VARCHAR(30) — tidak perlu migration untuk tambah status

---

## Detail Perubahan

### 8.1 Migration: tambah kolom QC di orders + order_id di quality_checks

**File baru:** `database/migrations/2026_09_15_XXXXXX_add_qc_fields_to_orders_and_quality_checks.php`

```php
public function up(): void
{
    // Tambah kolom QC di orders
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('jumlah_berhasil')->nullable()->after('produksi_catatan_tolak');
        $table->integer('jumlah_gagal')->nullable()->after('jumlah_berhasil');
        $table->datetime('tanggal_qc')->nullable()->after('jumlah_gagal');
        $table->datetime('tanggal_packing')->nullable()->after('tanggal_qc');
    });

    // Tambah order_id di quality_checks (link langsung ke orders)
    Schema::table('quality_checks', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->nullable()->after('production_order_id');
        $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete()->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('quality_checks', function (Blueprint $table) {
        $table->dropForeign(['order_id']);
        $table->dropColumn('order_id');
    });
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['jumlah_berhasil', 'jumlah_gagal', 'tanggal_qc', 'tanggal_packing']);
    });
}
```

### 8.2 Order Model: tambah konstanta + fillable + casts

**File:** `app/Models/Order.php`

```php
// Konstanta baru
public const STATUS_MENUNGGU_QC = 'menunggu_qc';
public const STATUS_SIAP_KIRIM = 'siap_kirim';

// Fillable tambahan (setelah 'produksi_catatan_tolak'):
'jumlah_berhasil',
'jumlah_gagal',
'tanggal_qc',
'tanggal_packing',

// Casts tambahan:
'tanggal_qc' => 'datetime',
'tanggal_packing' => 'datetime',
```

### 8.3 QualityCheck Model: tambah order_id + relasi

**File:** `app/Models/QualityCheck.php`

```php
// Fillable tambahan:
'order_id',  // tambah setelah 'production_order_id'

// Relasi baru:
public function order(): BelongsTo
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}
```

### 8.4 DataProduksiController: ubah updateStatus()

**File:** `app/Http/Controllers/Produksi/DataProduksiController.php`

**Sebelum:** `diproses` → `selesai`
**Sesudah:** `diproses` → `menunggu_qc`

```php
public function updateStatus(Request $request, Order $order)
{
    // ... guard sama seperti sekarang ...

    $data = $request->validate([
        'jumlah_berhasil' => 'required|integer|min:0',
        'jumlah_gagal' => 'nullable|integer|min:0',
        'catatan' => 'nullable|string|max:500',
    ]);

    // ... guard status harus diproses + accepted ...

    $lama = $order->only(['status']);
    $order->update([
        'status' => Order::STATUS_MENUNGGU_QC,
        'jumlah_berhasil' => $data['jumlah_berhasil'],
        'jumlah_gagal' => $data['jumlah_gagal'] ?? 0,
    ]);

    // Notifikasi ke Admin: "Produksi selesai, menunggu QC"
    // ...
}
```

### 8.5 PemeriksaanKualitasController: implement real

**File:** `app/Http/Controllers/Produksi/PemeriksaanKualitasController.php` — **Rewrite**

```php
class PemeriksaanKualitasController extends Controller
{
    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')->pluck('store_id')->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_MENUNGGU_QC)
            ->with(['items.productVariant.product', 'bahanList', 'checkout', 'store'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'menunggu_qc' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_MENUNGGU_QC)->count(),
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
        ];

        return view('Produksi.pemeriksaan-kualitas.index', compact('orders', 'stats'));
    }

    public function store(Request $request, Order $order)
    {
        // Guard: store scope + status harus menunggu_qc

        $data = $request->validate([
            'jumlah_lulus' => 'required|integer|min:0',
            'jumlah_gagal' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Create QualityCheck record
        QualityCheck::create([
            'order_id' => $order->order_id,
            'checked_by' => auth()->id(),
            'jumlah_lulus' => $data['jumlah_lulus'],
            'jumlah_gagal' => $data['jumlah_gagal'] ?? 0,
            'status' => $data['jumlah_gagal'] > 0 ? 'sebagian' : 'lulus',
            'catatan' => $data['catatan'] ?? null,
            'diperiksa_pada' => now(),
        ]);

        // Update order → siap_kirim + set tanggal_qc + tanggal_packing
        $order->update([
            'status' => Order::STATUS_SIAP_KIRIM,
            'tanggal_qc' => now(),
            'tanggal_packing' => now(),
        ]);

        // Notifikasi ke Admin: "Produk siap dikirim"
        NotificationService::sendToRole(Role::ADMIN, ...);

        return back()->with('toast', [
            'message' => "Pesanan {$order->nomor_order} lulus QC dan siap dikirim.",
            'icon' => 'task_alt',
        ]);
    }
}
```

### 8.6 ProdukSelesaiController: implement real

**File:** `app/Http/Controllers/Produksi/ProdukSelesaiController.php` — **Rewrite**

```php
class ProdukSelesaiController extends Controller
{
    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')->pluck('store_id')->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SIAP_KIRIM)
            ->with(['items.productVariant.product', 'qualityChecks' => function($q) {
                $q->where('order_id', '!=', null)->latest();
            }, 'checkout', 'store'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
            'dikirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_DIKIRIM)->count(),
        ];

        return view('Produksi.produk-selesai.index', compact('orders', 'stats'));
    }
}
```

### 8.7 Admin PengirimanController: update

**File:** `app/Http/Controllers/Admin/PengirimanController.php`

**index() — line 22-23:**

Sebelum:
```php
->where('status', Order::STATUS_DIPROSES)
```

Sesudah:
```php
->where('status', Order::STATUS_SIAP_KIRIM)
```

**simpanResi() — line 52:**

Sebelum:
```php
if ($pesanan->status !== Order::STATUS_DIPROSES) {
```

Sesudah:
```php
if ($pesanan->status !== Order::STATUS_SIAP_KIRIM) {
```

### 8.8 View: PemeriksaanKualitas (rewrite)

**File:** `resources/views/Produksi/pemeriksaan-kualitas/index.blade.php` — **Rewrite**

Theme: mengikuti `permintaan-produksi/index.blade.php` (card-premium, data-reveal, premium-table, skeleton/real pattern)

Struktur:
- Skeleton + data-real pattern
- Stats cards: Menunggu QC, Siap Kirim
- Tabel premium: No Pesanan, Produk & Jumlah, Hasil Produksi (berhasil/gagal), Status, Aksi
- Aksi: tombol "QC + Packing" → buka modal
- Modal per order: form input `jumlah_lulus`, `jumlah_gagal`, `catatan` → submit ke route `produksi.pemeriksaan-kualitas.store`
- Modal dirender di luar `<table>` (hindari HTML invalid)

### 8.9 View: ProdukSelesai (rewrite)

**File:** `resources/views/Produksi/produk-selesai/index.blade.php` — **Rewrite**

Theme: sama (card-premium, data-reveal, premium-table, skeleton/real)

Struktur:
- Skeleton + data-real pattern
- Stats cards: Siap Kirim, Sudah Dikirim
- Tabel premium: No Pesanan, Produk, Lulus QC, Gagal QC, Tanggal QC, Status
- Read-only (tidak ada aksi — sudah siap dikirim Admin)
- Menampilkan data dari `quality_checks` (jumlah_lulus, jumlah_gagal)

### 8.10 View: DataProduksi (update)

**File:** `resources/views/Produksi/data-produksi/index.blade.php`

Perubahan:
- Tambah `menunggu_qc` dan `siap_kirim` ke filter dropdown
- Tambah badge untuk status `menunggu_qc` (orange) dan `siap_kirim` (biru/hijau)
- Ubah tombol "Selesai" → buka modal form dengan input:
  - Jumlah Berhasil (number, required)
  - Jumlah Gagal (number, optional)
  - Catatan (textarea, optional)
- Ubah query `index()`: tambah `menunggu_qc` dan `siap_kirim` ke `whereIn` status
- Modal "Selesai" dirender di luar `<table>`

### 8.11 View: Admin Pengiriman (update minimal)

**File:** `resources/views/Admin/pengiriman/index.blade.php`

- Tidak perlu banyak perubahan — form resi sudah ada
- Pastikan label "Siap Kirim" tetap relevant (sekarang menampilkan order `siap_kirim` bukan `diproses`)

### 8.12 Routes

**File:** `routes/web.php`

Tambah di produksi group:

```php
Route::post('/pemeriksaan-kualitas/{order}/qc', [ProduksiPemeriksaan::class, 'store'])->name('pemeriksaan-kualitas.store');
```

### 8.13 Order Model: tambah relasi qualityChecks()

**File:** `app/Models/Order.php`

```php
public function qualityChecks(): HasMany
{
    return $this->hasMany(QualityCheck::class, 'order_id', 'order_id');
}
```

---

## File Terpengaruh Total

| # | File | Aksi |
|---|------|------|
| 1 | `database/migrations/2026_09_15_XXXXXX_add_qc_fields.php` | **Baru** |
| 2 | `app/Models/Order.php` | Edit: konstanta + fillable + casts + relasi qualityChecks() |
| 3 | `app/Models/QualityCheck.php` | Edit: fillable + relasi order() |
| 4 | `app/Http/Controllers/Produksi/DataProduksiController.php` | Edit: updateStatus() → menunggu_qc + input berhasil/gagal |
| 5 | `app/Http/Controllers/Produksi/PemeriksaanKualitasController.php` | **Rewrite**: index + store (QC + Packing) |
| 6 | `app/Http/Controllers/Produksi/ProdukSelesaiController.php` | **Rewrite**: index (list siap_kirim) |
| 7 | `app/Http/Controllers/Admin/PengirimanController.php` | Edit: STATUS_DIPROSES → STATUS_SIAP_KIRIM (2 method) |
| 8 | `resources/views/Produksi/data-produksi/index.blade.php` | Edit: status filter + modal selesai + badges |
| 9 | `resources/views/Produksi/pemeriksaan-kualitas/index.blade.php` | **Rewrite**: real data + form QC + packing |
| 10 | `resources/views/Produksi/produk-selesai/index.blade.php` | **Rewrite**: real data (siap kirim + QC results) |
| 11 | `resources/views/Admin/pengiriman/index.blade.php` | Edit minimal: guard status (sudah benar dari controller) |
| 12 | `routes/web.php` | Edit: tambah route QC store |

---

## Checklist Verifikasi

- [ ] `php -l` semua file — no syntax errors
- [ ] `php artisan migrate` — migration sukses (kolom QC + order_id di quality_checks)
- [ ] `php artisan view:clear`
- [ ] Order model: konstanta `STATUS_MENUNGGU_QC` + `STATUS_SIAP_KIRIM` ada
- [ ] Order model: relasi `qualityChecks()` ada
- [ ] QualityCheck model: `order_id` di fillable + relasi `order()` ada
- [ ] Produksi Data Produksi: tombol "Selesai" → buka modal dengan input jumlah berhasil/gagal
- [ ] Produksi klik "Selesai" → order jadi `menunggu_qc` (bukan `selesai`)
- [ ] Produksi: halaman Pemeriksaan Kualitas menampilkan order `menunggu_qc` (real data, bukan skeleton)
- [ ] Produksi: form QC + Packing → input lulus/gagal + catatan → order jadi `siap_kirim`
- [ ] Produksi: halaman Produk Selesai menampilkan order `siap_kirim` + hasil QC
- [ ] Admin: halaman Pengiriman menampilkan order `siap_kirim` di section "Siap Kirim"
- [ ] Admin: input resi + klik kirim → order jadi `dikirim`
- [ ] Sidebar Produksi: Pemeriksaan Kualitas & Produk Selesai tampil real data (bukan stub)
- [ ] Tinker render: pemeriksaan-kualitas + produk-selesai → no error
