# Rencana: Unifikasi Tema & Angka + Reset Transaksi Raliva Fashion

- **Status:** Draft — menunggu review user
- **Tanggal:** 25 Oktober 2026
- **Ruang lingkup:** Admin, Owner, Gudang, Produksi
- **Di luar lingkup:** Customer (tidak berubah), SuperAdmin (kecuali efek samping CSS yang dijelaskan di bawah)
- **Mode:** Plan only. Belum ada file aplikasi yang diubah dan belum ada perintah destruktif yang dijalankan.

---

## 1. Tujuan

1. Menyamakan tema burgundy/pink dan gaya angka di Admin, Owner, Gudang, dan Produksi dengan SuperAdmin sebagai referensi visual.
2. Semua angka netral memakai aksen:
   - Light mode: `#9B2D42`
   - Dark mode: `#E58AA0`
3. Merah/hijau tetap semantik:
   - Merah untuk error, negatif, stok kritis/habis, komplain terbuka, ditolak.
   - Hijau untuk success, positif, selesai, aktif.
4. Semua angka dan nominal yang tampil harus berasal dari data nyata, bukan hardcoded.
5. Reset semua data order/finansial ke nol tanpa menghapus master data:
   - users, stores, products, variants, warehouses, stock, suppliers, promos, settings tetap.
6. Tidak memakai `migrate:fresh`.
7. Satu file per commit, `--no-verify`, tidak push.
8. Tidak ada browser test; verifikasi visual dilakukan user.

---

## 2. Keputusan yang sudah dikunci

- Angka netral memakai aksen, bukan putih/gold.
- Merah/hijau tetap semantik.
- Reset bertarget, bukan fresh migration.
- Master data dipertahankan.
- Satu file per commit.
- Tidak push.
- Tidak browser test.
- `theme-head` dipakai bersama oleh Admin, Owner, Gudang, Produksi, dan SuperAdmin.
- Customer tidak memakai `partials.theme-head`, sehingga Customer otomatis tidak terpengaruh.

---

## 3. Temuan audit

### 3.1 Tema dan angka

**Penyebab utama dark mode tidak konsisten:**

Keempat layout staff meng-hardcode:

```css
--c-secondary: 139 30 63;
.text-secondary { color: rgb(139 30 63) !important; }
.text-secondary\/70 { color: rgba(139,30,63,0.7) !important; }
```

di kedua mode. Akibatnya di dark mode teks secondary tetap burgundy gelap, sementara `text-gold-accent` sudah berubah menjadi pink `#E58AA0`.

**Angka campur warna:**

- Sebagian memakai `text-on-surface` (putih/terang).
- Sebagian memakai `text-secondary` (burgundy).
- Sebagian memakai `text-gold-accent` (gold/pink).
- Sebagian memakai `text-error`/`text-success` (semantik).

**Elemen `.raliva-figure` non-angka:**

Ada 10 elemen, bukan 8:

1. `resources/views/Admin/profil/index.blade.php` — role
2. `resources/views/Admin/profil/index.blade.php` — status
3. `resources/views/Admin/dashboard-operasional/index.blade.php` — nama toko
4. `resources/views/Owner/dashboard/index.blade.php` — nama toko
5. `resources/views/Owner/data-toko/index.blade.php` — nama toko
6. `resources/views/Owner/pengajuan-toko/index.blade.php` — judul status
7. `resources/views/Owner/paket-slot/index.blade.php` — nama paket
8. `resources/views/Produksi/dashboard/index.blade.php` — nama atelier
9. `resources/views/layouts/owner.blade.php` — judul halaman mobile
10. `resources/views/layouts/produksi.blade.php` — judul halaman mobile

Dua yang terakhir ditemukan saat audit lanjutan. Jika tidak diberi marker, judul halaman mobile akan ikut berubah menjadi warna aksen.

**Angka di card gelap (`bg-deep-onyx text-on-primary`):**

Tiga angka tidak punya `text-on-primary`, sehingga jika aturan aksen global diterapkan, warnanya bisa salah di salah satu mode:

1. `resources/views/Owner/saldo/index.blade.php:57`
2. `resources/views/Owner/pencairan-dana/index.blade.php:22`
3. `resources/views/Owner/kelola-slot/index.blade.php:44`

**Efek samping SuperAdmin:**

Karena `layouts/superadmin.blade.php` juga meng-include `partials.theme-head`, aturan `.dark .text-secondary` akan membuat teks secondary SuperAdmin ikut pink. Ini meningkatkan kontras dan konsisten dengan aksen SuperAdmin, tetapi tetap perlu diketahui.

### 3.2 Data yang tidak akurat

#### Owner / Gudang

- Summary card menampilkan `Kapasitas Terpakai 63%` hardcoded.
- Card per gudang memakai `$summary['unit']` dan `$summary['menipis']` global, sehingga kedua gudang menampilkan angka yang sama.
- Card per gudang memakai `$g->produk` dan `$g->kapasitas` yang tidak ada di model/kolom, sehingga tampil kosong/0.

Data nyata saat audit:

- 2 gudang.
- 1.224 unit total.
- 148 baris stok/SKU.
- 59 baris stok menipis.
- Per gudang:
  - Bandung: 846 unit, 74 varian, 7 menipis.
  - Jakarta: 378 unit, 74 varian, 52 menipis.
- Kesehatan stok:
  - Total: `(148 - 59) / 148 = 60%`
  - Bandung: `(74 - 7) / 74 = 91%`
  - Jakarta: `(74 - 52) / 74 = 30%`

#### Owner / Pengiriman

- `Rata-rata Waktu Kirim` hardcoded `2,3 hari`.
- Header badge hardcoded `45 Berjalan`.
- Data nyata: 0 shipment, 0 sedang dikirim, 0 selesai dengan timestamp pengiriman.

#### Owner / Produksi

- Ringkasan dihitung hanya dari 12 order pada page pertama.
- Status `menunggu`/`pending` tidak ada di konstanta produksi.
- Status `dibatalkan` ikut dihitung sebagai berjalan.
- Bulan `(Agu)` hardcoded.
- Header badge `3 Berjalan` hardcoded.

Data nyata saat audit:

- 2 production order: 1 `diproses`, 1 `selesai`.
- 25 unit pada PO selesai bulan ini.
- 0 `requested`.
- Header seharusnya `1 Berjalan`.

#### Owner / Dashboard

- `dari 200 slot paket` hardcoded.
- `slot_grants` saat audit: 0 baris, sehingga kuota nyata 0.
- `buildAktivitas()`:
  - Status produksi memakai `pending`/`menunggu` yang tidak valid.
  - PO `dibatalkan` bisa tampil sebagai "Sedang berjalan".
  - Promo nominal menampilkan `Diskon X%` (salah satuan).
  - Progress promo hardcoded 56.

#### Admin & Owner / Promo

- `Rp 0` hardcoded.
- `18%` hardcoded.
- `+22%` / `+22% vs Juli` hardcoded.
- Tidak ada schema redemption/usage tracking promo.
- `orders` punya `total_diskon`, tetapi tidak ada `promotion_id`, sehingga konversi per promo tidak bisa dihitung tanpa perubahan schema.

Data nyata saat audit:

- 3 promo total.
- 2 aktif.
- 0 terjadwal.
- Total diskon order paid bulan ini: Rp 0.

#### Header badge hardcoded

| Halaman | Hardcoded | Data nyata |
|---|---|---|
| Owner / Pesanan | `18 Baru` | 5 |
| Owner / Pengiriman | `45 Berjalan` | 0 |
| Owner / Produksi | `3 Berjalan` | 1 |
| Owner / Karyawan | `6 Aktif` | 6 (kebetulan benar, tetap harus dinamis) |

#### Produksi / Dashboard

- Subtitle `12 staf aktif • Kapasitas 120 unit/hari` hardcoded.
- Tidak ada data kapasitas produksi atau jumlah staf aktif di database.

### 3.3 Data reset dan seeder

**Kondisi transaksi saat audit:**

| Tabel | Jumlah |
|---|---:|
| orders | 19 |
| checkouts | 19 |
| order_items | 23 |
| payments | 7 |
| payment_proofs | 4 |
| payment_verifications | 0 |
| refunds | 3 |
| refund_items | 0 |
| shipments | 0 |
| reviews | 4 |
| complaints | 3 |
| complaint_messages | 4 |
| commissions | 0 |
| wallet_transactions | 6 |
| withdrawals | 4 |
| store_expenses | 4 |
| ad_slots | 8 |
| slot_purchase_requests | 0 |
| customer_topups | 0 |
| customer_withdrawals | 0 |
| customer_wallet_transactions | 0 |
| activity_logs | 12 |
| notifications | 20 |
| quality_checks | 0 |
| production_order_bahan | 0 |
| permintaan_operasional | 0 |

**Kondisi master saat audit:**

| Tabel | Jumlah |
|---|---:|
| users | 21 |
| stores | 1 |
| store_staff | 6 |
| warehouses | 2 |
| warehouse_stocks | 148 |
| warehouse_staff | 6 |
| products | 10 |
| product_variants | 74 |
| product_images | 36 |
| categories | 30 |
| suppliers | 3 |
| bahan_produksi | 0 |
| promotions | 3 |
| settings | 10 |
| production_orders | 2 |
| production_order_items | 2 |
| stock_movements | 209 |
| stock_transfers | 1 |
| stock_transfer_items | 1 |
| banks | 5 |
| payment_methods | 4 |
| couriers | 5 |
| shipping_services | 0 |

**Wallet saat audit:**

- 1 store wallet: `saldo_tersedia` Rp 32.500.000, `saldo_tertahan` Rp 7.100.000.
- 1 customer wallet: `saldo_tersedia` 0.

**Seeder yang membuat transaksi demo:**

- `OwnerSeeder`: customer, order, order item, review, wallet, wallet transaction, store expense, complaint.
- `AdminDemoSeeder`: activity log, complaint message, refund, payment verification/proof.
- `GudangDemoSeeder`: demo orders, checkouts, order items, notifications.
- `RalivaDemoSeeder`: full demo data, termasuk transaksi.
- `ReviewSeeder`: review.
- `WalletSeeder`: wallet dan mutasi.
- `PeringkatIklanSeeder`: ad slot.

**Catatan penting:**

- `quality_checks.order_id` nullable dan FK-nya `SET NULL`; baris QC tetap dipertahankan, hanya `order_id` yang di-null-kan.
- `production_order_bahan.order_id` NOT NULL dan FK-nya `CASCADE`; baris order-specific harus dihapus.
- `slot_grants` dan `store_slot_subscriptions` saat audit kosong. Jika dihapus, kuota slot menjadi 0 dan Owner harus membeli/mendapat grant lagi.
- `permintaan_operasional` bukan transaksi finansial; saat audit 0 baris. Rencana: tidak dihapus.
- `carts`, `cart_items`, `wishlists`, `wishlist_items`, `store_follows` bukan transaksi finansial; saat audit 0. Rencana: tidak dihapus.
- File bukti pembayaran/ad slot di storage tidak otomatis dihapus jika hanya baris DB yang dihapus.

---

## 4. Rencana perubahan

### 4.1 Tema dan angka

**File:** `resources/views/partials/theme-head.blade.php`

Tambahkan variable:

```css
:root {
    --c-number-accent: #9B2D42;
}

.dark {
    --c-number-accent: #E58AA0;
}
```

Tambahkan aturan setelah aturan dark gold yang sudah ada:

```css
/* Angka & nominal staff: aksen konsisten SuperAdmin. */
main .raliva-figure:not(.raliva-figure-text) {
    color: var(--c-number-accent) !important;
}

/* Pengecualian kontras: angka di card gelap/terang mengikuti warna card. */
main .raliva-figure.text-on-primary {
    color: rgb(var(--c-on-primary)) !important;
}

/* Harga coret dan teks muted tetap muted. */
main .raliva-figure.text-on-surface-variant {
    color: rgb(var(--c-on-surface-variant)) !important;
}

/* Merah/hijau tetap semantik. */
main .raliva-figure.text-success {
    color: rgb(var(--c-success)) !important;
}

main .raliva-figure.text-error {
    color: rgb(var(--c-error)) !important;
}

/* Dark mode: teks secondary jadi pink, bukan burgundy gelap. */
.dark .text-secondary {
    color: #E58AA0 !important;
}

.dark .text-secondary\/70 {
    color: rgba(229, 138, 160, .7) !important;
}
```

**Alasan urutan:**

- Base selector `main .raliva-figure:not(.raliva-figure-text)` memiliki specificity `0,2,1`.
- Exception `.raliva-figure.text-on-primary` juga `0,2,1`; karena ditulis setelahnya, exception menang.
- Jangan membuat rantai `:not()` untuk semua pengecualian karena specificity-nya bisa mengalahkan aturan exception.
- `.dark .text-secondary` specificity `0,2,0` mengalahkan `.text-secondary` layout `0,1,0` meskipun layout dimuat setelah `theme-head`.

**Marker `raliva-figure-text`:**

Tambahkan class ke 10 elemen non-angka:

1. `resources/views/Admin/profil/index.blade.php` — role dan status.
2. `resources/views/Admin/dashboard-operasional/index.blade.php` — nama toko.
3. `resources/views/Owner/dashboard/index.blade.php` — nama toko.
4. `resources/views/Owner/data-toko/index.blade.php` — nama toko.
5. `resources/views/Owner/pengajuan-toko/index.blade.php` — judul status.
6. `resources/views/Owner/paket-slot/index.blade.php` — nama paket.
7. `resources/views/Produksi/dashboard/index.blade.php` — nama atelier.
8. `resources/views/layouts/owner.blade.php` — judul halaman mobile.
9. `resources/views/layouts/produksi.blade.php` — judul halaman mobile.

**Catatan:** Admin/profil punya dua elemen (role dan status), jadi total elemen 10 tetapi file-nya 9.

**Tambahan `text-on-primary`:**

1. `resources/views/Owner/saldo/index.blade.php:57`
2. `resources/views/Owner/pencairan-dana/index.blade.php:22`
3. `resources/views/Owner/kelola-slot/index.blade.php:44`

**Efek samping yang diterima:**

- SuperAdmin dark mode: teks secondary menjadi pink.
- Customer: tidak berubah.

### 4.2 Perbaikan data

#### A. Owner / Gudang

**File:** `app/Http/Controllers/Owner/GudangController.php`

Ganti summary `kapasitas` dengan perhitungan per gudang:

```php
$warehouses = Warehouse::with('staff')
    ->withCount(['stocks as total_sku'])
    ->withCount([
        'stocks as menipis_sku' => fn ($q) =>
            $q->whereColumn('jumlah_stok', '<=', 'stok_minimum'),
    ])
    ->withSum('stocks as unit_stok', 'jumlah_stok')
    ->where('store_id', $storeId)
    ->get();

$warehouses->each(function (Warehouse $warehouse) {
    $warehouse->setAttribute('unit', (int) ($warehouse->unit_stok ?? 0));
    $warehouse->setAttribute('variants', (int) ($warehouse->total_sku ?? 0));
    $warehouse->setAttribute('menipis', (int) ($warehouse->menipis_sku ?? 0));
    $warehouse->setAttribute(
        'health',
        $warehouse->variants > 0
            ? (int) round((($warehouse->variants - $warehouse->menipis) / $warehouse->variants) * 100)
            : 0
    );
});

$totalVariants = $warehouses->sum('variants');
$totalMenipis = $warehouses->sum('menipis');

$summary = [
    'total' => $warehouses->count(),
    'unit' => $warehouses->sum('unit'),
    'menipis' => $totalMenipis,
    'health' => $totalVariants > 0
        ? (int) round((($totalVariants - $totalMenipis) / $totalVariants) * 100)
        : 0,
];
```

**File:** `resources/views/Owner/gudang/index.blade.php`

- Summary card 3:
  - Label `Kapasitas Terpakai` → `Kesehatan Stok`
  - Nilai `<span>63</span>%` → `{{ $summary['health'] }}%`
  - `data-progress="63"` → `data-progress="{{ $summary['health'] }}"`
- Card per gudang:
  - Label `Kapasitas Terpakai` → `Kesehatan Stok`
  - `$g->kapasitas` → `$g->health`
  - `data-progress="{{ $g->kapasitas }}"` → `data-progress="{{ $g->health }}"`
  - `number_format($summary['unit'], ...)` → `number_format($g->unit, ...)`
  - `$g->produk` → `$g->variants`
  - `$summary['menipis']` → `$g->menipis`

**Hasil yang diharapkan:**

- Summary: 2 gudang, 1.224 unit, 59 menipis, 60% health.
- Bandung: 846 unit, 74 varian, 7 menipis, 91%.
- Jakarta: 378 unit, 74 varian, 52 menipis, 30%.

#### B. Owner / Pengiriman

**File:** `app/Http/Controllers/Owner/PengirimanController.php`

Tambahkan import `App\Models\Shipment` dan hitung rata-rata:

```php
$rataHari = Shipment::query()
    ->whereHas('order', fn ($q) => $q->where('store_id', $storeId))
    ->whereNotNull('dikirim_pada')
    ->whereNotNull('diterima_pada')
    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, dikirim_pada, diterima_pada) / 24) as rata')
    ->value('rata');

$summary = [
    'dikirim' => $dikirim,
    'terkirim' => $terkirimHariIni,
    'menunggu' => $menungguKurir,
    'rata_hari' => $rataHari !== null ? round((float) $rataHari, 1) : null,
];
```

**File:** `resources/views/Owner/pengiriman/index.blade.php`

- Header badge:
  - `@section('header-badge', __('45 Berjalan'))`
  - → `@section('header-badge', $summary['dikirim'] . ' ' . __('Berjalan'))`
- Card rata-rata:
  - Jika `$summary['rata_hari'] !== null`: tampilkan `number_format($summary['rata_hari'], 1, ',', '.')` + `hari`.
  - Jika null: tampilkan `—` dengan class `text-on-surface-variant`.

**Hasil yang diharapkan saat data kosong:** `—`, badge `0 Berjalan`.

#### C. Admin & Owner / Promo

**File:** `app/Http/Controllers/Admin/PromoController.php`

Tambahkan import `App\Models\Order` dan:

```php
$totalDiskon = (float) Order::whereIn('store_id', $storeIds)
    ->whereIn('status', [
        Order::STATUS_DIBAYAR,
        Order::STATUS_DIPROSES,
        Order::STATUS_DIKIRIM,
        Order::STATUS_SELESAI,
    ])
    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
    ->sum('total_diskon');

return view('Admin.promo.index', compact('promos', 'counts', 'store', 'totalDiskon'));
```

**File:** `app/Http/Controllers/Owner/PromoController.php`

Sama, tetapi `where('store_id', $storeId)`.

**File:** `resources/views/Admin/promo/index.blade.php` dan `resources/views/Owner/promo/index.blade.php`

- Card 2:
  - Label `Penukaran Bulan Ini` → `Total Promo`
  - Nilai tetap `$counts['total']`
  - Subtext `+22% ...` → `{{ $counts['aktif'] }} {{ __('aktif') }} · {{ $counts['terjadwal'] }} {{ __('terjadwal') }}`
- Card 3:
  - Label `Estimasi Diskon Diberikan` → `Diskon Bulan Ini`
  - Nilai `Rp 0` → `Rp {{ number_format($totalDiskon, 0, ',', '.') }}`
- Card 4:
  - Label `Konversi Promo` → `Promo Terjadwal`
  - Nilai `18%` → `$counts['terjadwal']` tanpa `%`
  - Icon `query_stats` → `event_upcoming`

**Alasan:** tidak ada schema redemption tracking. `orders.total_diskon` adalah satu-satunya sumber diskon nyata. Perubahan schema tidak termasuk scope.

**Hasil yang diharapkan saat data sekarang:** 3 total promo, 2 aktif, 0 terjadwal, Rp 0 diskon.

#### D. Owner / Dashboard

**File:** `app/Http/Controllers/Owner/DashboardController.php`

1. Tambahkan import `App\Support\SlotService`.
2. Hitung:

```php
$totalSlot = $storeId ? SlotService::totalQuota($storeId) : 0;
```

3. Masukkan `totalSlot` ke `compact()`.
4. Perbaiki `buildAktivitas()`:

**Produksi:**

```php
$produksi = ProductionOrder::where('store_id', $storeId)
    ->whereNotIn('status', [
        ProductionOrder::STATUS_SELESAI,
        ProductionOrder::STATUS_DIBATALKAN,
    ])
    ->latest()
    ->first();

if ($produksi) {
    $p = match ($produksi->status) {
        ProductionOrder::STATUS_DIPROSES => 55,
        ProductionOrder::STATUS_REQUESTED => 15,
        ProductionOrder::STATUS_MENUNGGU_QC => 80,
        default => 30,
    };

    $subtitle = match ($produksi->status) {
        ProductionOrder::STATUS_REQUESTED => 'Menunggu persetujuan',
        ProductionOrder::STATUS_MENUNGGU_QC => 'Menunggu pemeriksaan QC',
        default => 'Sedang berjalan',
    };
}
```

**Promo:**

```php
$promo = Promotion::where('store_id', $storeId)
    ->where('status', 'aktif')
    ->where('mulai_pada', '<=', Carbon::today())
    ->where('berakhir_pada', '>=', Carbon::today())
    ->latest()
    ->first();

if ($promo) {
    $sisa = Carbon::today()->diffInDays($promo->berakhir_pada, false);

    $diskon = $promo->tipe_diskon === 'persen'
        ? $promo->nilai_diskon . '%'
        : 'Rp ' . number_format((float) $promo->nilai_diskon, 0, ',', '.');

    $totalHari = max(1, $promo->mulai_pada->diffInDays($promo->berakhir_pada));
    $berjalan = max(0, $promo->mulai_pada->diffInDays(Carbon::today()));
    $progress = (int) min(100, max(0, round($berjalan / $totalHari * 100)));
}
```

**File:** `resources/views/Owner/dashboard/index.blade.php`

- Tambahkan `raliva-figure-text` ke nama toko.
- Ganti:

```blade
{{ __('dari 200 slot paket') }}
```

menjadi:

```blade
{{ $totalSlot > 0
    ? __('dari :total slot paket', ['total' => number_format($totalSlot, 0, ',', '.')])
    : __('belum ada slot paket') }}
```

**Hasil yang diharapkan:** `Produk Aktif 10` + `belum ada slot paket` karena `slot_grants` kosong.

#### E. Owner / Produksi

**File:** `app/Http/Controllers/Owner/ProduksiController.php`

Tambahkan import `App\Models\ProductionOrderItem`.

Ganti perhitungan loop page dengan query agregat:

```php
$storeId = OwnerContext::firstStoreId();
$startOfMonth = now()->startOfMonth();
$endOfMonth = now()->endOfMonth();

$base = ProductionOrder::where('store_id', $storeId);

$summary = [
    'berjalan' => (clone $base)
        ->whereIn('status', [
            ProductionOrder::STATUS_DIPROSES,
            ProductionOrder::STATUS_MENUNGGU_QC,
        ])
        ->count(),

    'selesai' => (clone $base)
        ->where('status', ProductionOrder::STATUS_SELESAI)
        ->whereBetween('selesai_pada', [$startOfMonth, $endOfMonth])
        ->count(),

    'unit' => (int) ProductionOrderItem::whereHas(
        'productionOrder',
        function ($q) use ($storeId, $startOfMonth, $endOfMonth) {
            $q->where('store_id', $storeId)
                ->where('status', ProductionOrder::STATUS_SELESAI)
                ->whereBetween('selesai_pada', [$startOfMonth, $endOfMonth]);
        }
    )->sum('jumlah_diminta'),

    'menunggu' => (clone $base)
        ->where('status', ProductionOrder::STATUS_REQUESTED)
        ->count(),
];
```

**File:** `resources/views/Owner/produksi/index.blade.php`

- Header badge:
  - `@section('header-badge', __('3 Berjalan'))`
  - → `@section('header-badge', $summary['berjalan'] . ' ' . __('Berjalan'))`
- Label unit:
  - `Unit Diproduksi (Agu)`
  - → `Unit Diproduksi ({{ now()->locale('id')->translatedFormat('M') }})`

**Hasil yang diharapkan:** 1 berjalan, 1 selesai, 25 unit, 0 menunggu, badge `1 Berjalan`, bulan `Okt`.

#### F. Header badge lain

**File:** `resources/views/Owner/pesanan/index.blade.php`

```blade
@section('header-badge', $counts['baru'] . ' ' . __('Baru'))
```

**File:** `resources/views/Owner/karyawan/index.blade.php`

```blade
@section('header-badge', ($summary['total'] - $summary['nonaktif']) . ' ' . __('Aktif'))
```

#### G. Produksi / Dashboard

**File:** `resources/views/Produksi/dashboard/index.blade.php`

- Tambahkan `raliva-figure-text` ke nama atelier.
- Ganti subtitle hardcoded:

```blade
{{ __('12 staf aktif • Kapasitas 120 unit/hari') }}
```

menjadi:

```blade
{{ __('Kelola produksi, pemeriksaan kualitas, dan hasil kerja atelier.') }}
```

### 4.3 Command reset transaksi

**File baru:** `app/Console/Commands/ResetTransaksiCommand.php`

**Signature:**

```php
protected $signature = 'raliva:reset-transaksi
    {--force : Jalankan tanpa konfirmasi}
    {--dry-run : Tampilkan jumlah baris tanpa menghapus}';
```

**Perilaku:**

1. Tampilkan peringatan bahwa command destruktif dan harus backup.
2. Jika bukan `--force`, minta konfirmasi.
3. Jika `--dry-run`, tampilkan count tabel lalu return tanpa perubahan.
4. Disable foreign key checks.
5. Jalankan delete/update dalam `DB::transaction`.
6. Enable foreign key checks di `finally`.
7. Tampilkan ringkasan hasil.

**Urutan operasi:**

```text
1.  quality_checks: set order_id = null where order_id is not null
2.  production_order_bahan: delete all
3.  customer_wallet_transactions: delete all
4.  customer_topups: delete all
5.  customer_withdrawals: delete all
6.  wallet_transactions: delete all
7.  withdrawals: delete all
8.  commissions: delete all
9.  refund_items: delete all
10. refunds: delete all
11. payment_proofs: delete all
12. payment_verifications: delete all
13. payments: delete all
14. shipments: delete all
15. reviews: delete all
16. complaint_messages: delete all
17. complaints: delete all
18. order_items: delete all
19. orders: delete all
20. checkouts: delete all
21. store_expenses: delete all
22. ad_slots: delete all
23. slot_purchase_requests: delete all
24. slot_grants: delete all
25. store_slot_subscriptions: delete all
26. activity_logs: delete all
27. notifications: delete all
28. wallets: set saldo_tersedia = 0, saldo_tertahan = 0
29. customer_wallets: set saldo_tersedia = 0
30. warehouse_stocks: set jumlah_direservasi = 0
```

**Tabel yang dihapus:**

```text
orders
checkouts
order_items
payments
payment_proofs
payment_verifications
refunds
refund_items
shipments
reviews
complaints
complaint_messages
commissions
wallet_transactions
withdrawals
store_expenses
ad_slots
slot_purchase_requests
slot_grants
store_slot_subscriptions
customer_topups
customer_withdrawals
customer_wallet_transactions
activity_logs
notifications
production_order_bahan
```

**Tabel yang di-null-kan:**

```text
quality_checks.order_id
```

**Tabel yang di-zero-kan:**

```text
wallets.saldo_tersedia
wallets.saldo_tertahan
customer_wallets.saldo_tersedia
warehouse_stocks.jumlah_direservasi
```

**Tabel yang dipertahankan:**

```text
users
roles
permissions
role_permissions
stores
store_staff
store_staff_permissions
store_documents
store_socials
store_categories
store_category_sizes
store_courier_settings
store_bank_accounts
products
product_variants
product_images
categories
suppliers
supplier_bahan
bahan_produksi
promotions
promotion_categories
promotion_products
settings
warehouses
warehouse_stocks
warehouse_staff
warehouse_staff_permissions
production_orders
production_order_items
production_results
production_order_materials
production_materials
material_movements
quality_checks (baris tetap)
stock_movements
stock_transfers
stock_transfer_items
stock_damage
stock_opname
banks
payment_methods
couriers
shipping_services
platform_bank_accounts
product_slot_packages
slot_package_promotions
product_material_requirements
product_operational_costs
product_update_requests
store_update_requests
help_categories
help_faqs
landing_faqs
legal_documents
cities
sosmed_platforms
addresses
```

**Tidak dihapus, tetapi saat audit 0:**

```text
permintaan_operasional
carts
cart_items
wishlists
wishlist_items
store_follows
```

**File bukti:**

Command hanya menghapus baris DB. File bukti pembayaran/ad slot di storage tidak dihapus otomatis. Jika ingin bersih total, bisa ditambahkan opsi `--purge-files` di fase terpisah.

### 4.4 Seeder

**File:** `database/seeders/DatabaseSeeder.php`

Keluarkan dari default flow:

```text
ReviewSeeder
WalletSeeder
RalivaDemoSeeder
PeringkatIklanSeeder
```

Tetap di default flow:

```text
RoleSeeder
PermissionSeeder
RolePermissionSeeder
DataBankSeeder
CourierSeeder
CitySeeder
CategorySeeder
StoreCategorySeeder
StoreCategorySizeSeeder
SuperAdminSeeder
GudangDemoSeeder (guarded)
UserSeeder
OwnerSeeder (guarded)
ProductSeeder
PermintaanProduksiSeeder
AdminDemoSeeder (guarded)
WarehouseStaffPermissionSeeder
HelpCenterSeeder
LegalDocumentSeeder
BarangKeluarSeeder
```

**File:** `database/seeders/OwnerSeeder.php`

Guard blok:

1. Dari komentar `---- PELANGGAN + PESANAN` sampai sebelum `---- DOKUMEN TOKO`:
   - customer
   - order
   - order item
   - review
   - wallet
   - wallet transaction
   - store expense
2. Blok `---- KOMPLAIN` sampai sebelum `---- PERMINTAAN PRODUKSI`.

Tetap tanpa guard:

- Dokumen toko.
- Permintaan produksi.
- Produk.
- Promo.

**File:** `database/seeders/AdminDemoSeeder.php`

Guard blok:

1. ActivityLog.
2. ComplaintMessage.
3. Refund.
4. Verifikasi pembayaran + payment proof.

Tetap tanpa guard:

- Supplier.
- Bahan baku.
- Warehouse stock.

**File:** `database/seeders/GudangDemoSeeder.php`

Guard blok:

- Dari `$customer = ...` (demo customer).
- Pembuatan checkout/order/order item.
- Notifikasi untuk user gudang.

Tetap tanpa guard:

- Warehouse.
- Warehouse staff.
- Product/stock demo.
- Stock movement.
- Stock transfer.

**Helper flag:**

Gunakan inline di setiap seeder:

```php
if (filter_var(env('RALIVA_SEED_DEMO_TRANSACTIONS', false), FILTER_VALIDATE_BOOLEAN)) {
    // blok transaksi demo
}
```

**File:** `.env.example`

Tambahkan:

```dotenv
# Seed demo transaksi (pesanan, pembayaran, wallet, dll).
# Set true hanya untuk demo. Default false agar migrate:fresh --seed bersih.
RALIVA_SEED_DEMO_TRANSACTIONS=false
```

### 4.5 Backup dan verifikasi

**Backup sebelum reset:**

```powershell
$ts = Get-Date -Format "yyyyMMdd_HHmmss"
& "C:\laragon\bin\mysql\mysql-5.7.39-winx64\bin\mysqldump.exe" `
  -u root -h 127.0.0.1 `
  --single-transaction --routines --triggers `
  Raliva_Fashion > "C:\Users\acer\AppData\Local\Temp\opencode\raliva_fashion_backup_$ts.sql"
```

**Dry run:**

```powershell
php artisan raliva:reset-transaksi --dry-run
```

**Eksekusi:**

```powershell
php artisan raliva:reset-transaksi --force
```

**Verifikasi transaksi = 0:**

```sql
SELECT 'orders' t, COUNT(*) c FROM orders
UNION ALL SELECT 'checkouts', COUNT(*) FROM checkouts
UNION ALL SELECT 'order_items', COUNT(*) FROM order_items
UNION ALL SELECT 'payments', COUNT(*) FROM payments
UNION ALL SELECT 'payment_proofs', COUNT(*) FROM payment_proofs
UNION ALL SELECT 'payment_verifications', COUNT(*) FROM payment_verifications
UNION ALL SELECT 'refunds', COUNT(*) FROM refunds
UNION ALL SELECT 'refund_items', COUNT(*) FROM refund_items
UNION ALL SELECT 'shipments', COUNT(*) FROM shipments
UNION ALL SELECT 'reviews', COUNT(*) FROM reviews
UNION ALL SELECT 'complaints', COUNT(*) FROM complaints
UNION ALL SELECT 'complaint_messages', COUNT(*) FROM complaint_messages
UNION ALL SELECT 'commissions', COUNT(*) FROM commissions
UNION ALL SELECT 'wallet_transactions', COUNT(*) FROM wallet_transactions
UNION ALL SELECT 'withdrawals', COUNT(*) FROM withdrawals
UNION ALL SELECT 'store_expenses', COUNT(*) FROM store_expenses
UNION ALL SELECT 'ad_slots', COUNT(*) FROM ad_slots
UNION ALL SELECT 'slot_purchase_requests', COUNT(*) FROM slot_purchase_requests
UNION ALL SELECT 'slot_grants', COUNT(*) FROM slot_grants
UNION ALL SELECT 'store_slot_subscriptions', COUNT(*) FROM store_slot_subscriptions
UNION ALL SELECT 'customer_topups', COUNT(*) FROM customer_topups
UNION ALL SELECT 'customer_withdrawals', COUNT(*) FROM customer_withdrawals
UNION ALL SELECT 'customer_wallet_transactions', COUNT(*) FROM customer_wallet_transactions
UNION ALL SELECT 'activity_logs', COUNT(*) FROM activity_logs
UNION ALL SELECT 'notifications', COUNT(*) FROM notifications
UNION ALL SELECT 'production_order_bahan', COUNT(*) FROM production_order_bahan;
```

**Verifikasi wallet dan reservasi:**

```sql
SELECT COALESCE(SUM(saldo_tersedia),0) AS saldo_tersedia,
       COALESCE(SUM(saldo_tertahan),0) AS saldo_tertahan
FROM wallets;

SELECT COALESCE(SUM(saldo_tersedia),0) AS saldo_customer
FROM customer_wallets;

SELECT COALESCE(SUM(jumlah_direservasi),0) AS total_reserved
FROM warehouse_stocks;
```

**Verifikasi master tidak berubah:**

```sql
SELECT 'users' t, COUNT(*) c FROM users
UNION ALL SELECT 'stores', COUNT(*) FROM stores
UNION ALL SELECT 'products', COUNT(*) FROM products
UNION ALL SELECT 'product_variants', COUNT(*) FROM product_variants
UNION ALL SELECT 'warehouses', COUNT(*) FROM warehouses
UNION ALL SELECT 'warehouse_stocks', COUNT(*) FROM warehouse_stocks
UNION ALL SELECT 'suppliers', COUNT(*) FROM suppliers
UNION ALL SELECT 'promotions', COUNT(*) FROM promotions
UNION ALL SELECT 'settings', COUNT(*) FROM settings
UNION ALL SELECT 'production_orders', COUNT(*) FROM production_orders
UNION ALL SELECT 'production_order_items', COUNT(*) FROM production_order_items
UNION ALL SELECT 'stock_movements', COUNT(*) FROM stock_movements
UNION ALL SELECT 'stock_transfers', COUNT(*) FROM stock_transfers;
```

**Target master setelah reset:**

```text
users 21
stores 1
products 10
product_variants 74
warehouses 2
warehouse_stocks 148
suppliers 3
promotions 3
settings 10
production_orders 2
production_order_items 2
stock_movements 209
stock_transfers 1
```

**Compile view:**

```powershell
php artisan view:cache
```

**Test terarah:**

```powershell
php artisan test --filter=ProfileSmokeTest
php artisan test --filter=PengaturanSistemBrandingTest
```

**Tidak dilakukan:**

- Browser test.
- Full test suite (ada failure unrelated yang sudah diketahui: `BrandNameTest` favicon fallback, `RefundApproveAtomicTest`, `SessionExpiryTest`).

---

## 5. Rencana commit — 32 file

Setiap commit hanya satu file, memakai `--no-verify`, tidak push.

### Fase 1 — marker & kontras (11 commit)

| # | File | Perubahan |
|---:|---|---|
| 1 | `resources/views/Admin/profil/index.blade.php` | `raliva-figure-text` role/status |
| 2 | `resources/views/Admin/dashboard-operasional/index.blade.php` | `raliva-figure-text` nama toko |
| 3 | `resources/views/Owner/data-toko/index.blade.php` | `raliva-figure-text` nama toko |
| 4 | `resources/views/Owner/pengajuan-toko/index.blade.php` | `raliva-figure-text` status |
| 5 | `resources/views/Owner/paket-slot/index.blade.php` | `raliva-figure-text` nama paket |
| 6 | `resources/views/Produksi/dashboard/index.blade.php` | `raliva-figure-text` atelier + subtitle generik |
| 7 | `resources/views/layouts/owner.blade.php` | `raliva-figure-text` judul mobile |
| 8 | `resources/views/layouts/produksi.blade.php` | `raliva-figure-text` judul mobile |
| 9 | `resources/views/Owner/saldo/index.blade.php` | `text-on-primary` |
| 10 | `resources/views/Owner/pencairan-dana/index.blade.php` | `text-on-primary` |
| 11 | `resources/views/Owner/kelola-slot/index.blade.php` | `text-on-primary` |

### Fase 2 — data (14 commit)

| # | File | Perubahan |
|---:|---|---|
| 12 | `app/Http/Controllers/Owner/GudangController.php` | health/unit/varian per gudang |
| 13 | `resources/views/Owner/gudang/index.blade.php` | tampilkan data per gudang |
| 14 | `app/Http/Controllers/Owner/PengirimanController.php` | rata-rata shipment nyata |
| 15 | `resources/views/Owner/pengiriman/index.blade.php` | `—`, badge real |
| 16 | `app/Http/Controllers/Admin/PromoController.php` | total diskon real |
| 17 | `resources/views/Admin/promo/index.blade.php` | kartu promo real |
| 18 | `app/Http/Controllers/Owner/PromoController.php` | total diskon real |
| 19 | `resources/views/Owner/promo/index.blade.php` | kartu promo real |
| 20 | `app/Http/Controllers/Owner/DashboardController.php` | slot total + activity fix |
| 21 | `resources/views/Owner/dashboard/index.blade.php` | slot total + marker nama toko |
| 22 | `app/Http/Controllers/Owner/ProduksiController.php` | summary seluruh PO |
| 23 | `resources/views/Owner/produksi/index.blade.php` | badge + bulan dinamis |
| 24 | `resources/views/Owner/pesanan/index.blade.php` | badge `baru` |
| 25 | `resources/views/Owner/karyawan/index.blade.php` | badge aktif |

### Fase 3 — CSS (1 commit)

| # | File | Perubahan |
|---:|---|---|
| 26 | `resources/views/partials/theme-head.blade.php` | `--c-number-accent`, aturan angka, dark secondary |

CSS sengaja di-commit terakhir agar semua marker sudah terpasang.

### Fase 4 — reset & seeder (6 commit)

| # | File | Perubahan |
|---:|---|---|
| 27 | `app/Console/Commands/ResetTransaksiCommand.php` | command baru |
| 28 | `database/seeders/DatabaseSeeder.php` | keluarkan seeder transaksi |
| 29 | `database/seeders/OwnerSeeder.php` | guard transaksi |
| 30 | `database/seeders/AdminDemoSeeder.php` | guard transaksi |
| 31 | `database/seeders/GudangDemoSeeder.php` | guard order/notifikasi |
| 32 | `.env.example` | flag `RALIVA_SEED_DEMO_TRANSACTIONS` |

**Catatan:** reset DB bukan commit. Backup berada di luar repo.

---

## 6. Fase D opsional — nominal di tabel

Ini belum masuk 32 commit di atas. Perlu keputusan user.

**Rekomendasi:** tambahkan `.raliva-num` hanya ke tabel finansial utama, bukan seluruh 296 kemunculan `number_format`.

Kandidat file:

```text
resources/views/Owner/pesanan/index.blade.php
resources/views/Admin/pesanan/index.blade.php
resources/views/Admin/verifikasi-pembayaran/index.blade.php
resources/views/Owner/produk/index.blade.php
resources/views/Admin/produk/index.blade.php
resources/views/Owner/saldo/index.blade.php
resources/views/Owner/pencairan-dana/index.blade.php
resources/views/Owner/kelola-slot/index.blade.php
resources/views/Owner/pengiriman/index.blade.php
resources/views/Owner/paket-slot/index.blade.php
resources/views/Admin/pengembalian-dana/index.blade.php
resources/views/Owner/pengembalian-dana/index.blade.php
resources/views/Owner/peringkat-iklan/index.blade.php
resources/views/Gudang/stok/index.blade.php
resources/views/Owner/laporan/index.blade.php
resources/views/Admin/laporan/index.blade.php
resources/views/Owner/data-pelanggan/index.blade.php
resources/views/Admin/customer/index.blade.php
resources/views/Owner/pengikut/index.blade.php
```

**Aturan CSS:**

```css
main .raliva-num {
    color: var(--c-number-accent) !important;
    font-variant-numeric: tabular-nums;
}
```

**Yang tidak disentuh:**

- PDF, invoice, cetak.
- Label non-angka.
- Tombol/status.
- Nominal yang sudah semantik merah/hijau.

**Full literal:** sekitar 296 `number_format` di ~45 file, termasuk dokumen. Tidak direkomendasikan untuk satu batch.

---

## 7. Keputusan yang masih terbuka

1. **Nominal tabel:** core (hanya `.raliva-figure`) atau core + tabel finansial utama (`.raliva-num`)?
   - Rekomendasi: core + tabel finansial utama.
2. **SuperAdmin dark secondary:** terima ikut pink, atau scope aturan hanya ke 4 layout staff?
   - Rekomendasi: terima ikut pink.
3. **Harga kurir statis** (`Rp 12rb-28rb`, dll.) dan **target chart produksi** `40/280/720`:
   - Rekomendasi: tetap sebagai marketing/target constant.
4. **Mini bar chart Gudang** yang hardcoded height:
   - Rekomendasi: biarkan karena tidak menampilkan angka. Bisa diganti data `$chart` di fase terpisah.
5. **`permintaan_operasional`**:
   - Rekomendasi: tidak dihapus karena bukan transaksi finansial dan saat ini 0.

---

## 8. Risiko dan rollback

| Risiko | Mitigasi |
|---|---|
| Reset menghapus data nyata | Backup `mysqldump` sebelum eksekusi |
| FK violation saat delete | Disable FK checks + urutan child-first |
| `slot_grants` dihapus → kuota 0 | Saat audit 0; jika ada data, Owner harus repurchase/grant ulang |
| `quality_checks` kehilangan relasi order | Hanya `order_id` di-null-kan; baris QC tetap |
| `production_order_bahan` hilang | Saat audit 0; memang order-specific |
| SuperAdmin ikut berubah | Diterima atau scope CSS ke staff |
| Tampilan sementara antar commit | CSS di-commit terakhir setelah marker |
| Seeder manual masih membuat transaksi | Flag hanya mengatur default seeder; `RalivaDemoSeeder` tetap opt-in |
| File bukti orphan | Command hanya hapus DB; file dibersihkan terpisah jika perlu |
| Data berubah antara audit dan eksekusi | Jalankan `--dry-run` dan cek count ulang sebelum `--force` |

**Rollback DB:**

```powershell
& "C:\laragon\bin\mysql\mysql-5.7.39-winx64\bin\mysql.exe" -u root -h 127.0.0.1 Raliva_Fashion < "C:\Users\acer\AppData\Local\Temp\opencode\raliva_fashion_backup_<timestamp>.sql"
```

**Rollback kode:** `git reset --hard <commit-sebelum-perubahan>` atau revert commit satu per satu. Tidak push, jadi aman.

---

## 9. Definition of done

- [ ] Semua angka ringkasan/hero di Admin, Owner, Gudang, Produksi memakai aksen burgundy/pink.
- [ ] Merah/hijau tetap semantik.
- [ ] Tidak ada teks non-angka yang ikut berubah warna.
- [ ] Angka di card gelap tetap kontras di light dan dark mode.
- [ ] Dark mode `text-secondary` menjadi pink.
- [ ] Owner/Gudang menampilkan 60% health, 91%/30% per gudang, 1.224 unit, 59 menipis.
- [ ] Owner/Pengiriman menampilkan `—` dan badge real.
- [ ] Owner/Produksi menampilkan 1 berjalan, 1 selesai, 25 unit, 0 menunggu, bulan dinamis.
- [ ] Owner/Dashboard menampilkan kuota slot nyata dan activity yang akurat.
- [ ] Admin/Owner Promo menampilkan 3 total, 2 aktif, 0 terjadwal, Rp 0 diskon.
- [ ] Header badge Pesanan/Karyawan/Pengiriman/Produksi dinamis.
- [ ] Produksi Dashboard tidak lagi menampilkan 12 staf/120 unit.
- [ ] Reset command berhasil; semua tabel transaksi 0.
- [ ] Wallet dan reservasi 0.
- [ ] Master count tidak berubah.
- [ ] `migrate:fresh --seed` tidak membuat transaksi demo secara default.
- [ ] `php artisan view:cache` sukses.
- [ ] `ProfileSmokeTest` dan `PengaturanSistemBrandingTest` sukses.
- [ ] 32 commit, satu file per commit, `--no-verify`, tidak push.

---

## 10. Langkah eksekusi setelah plan disetujui

1. Backup DB.
2. Commit Fase 1 (11 file).
3. Commit Fase 2 (14 file).
4. Commit Fase 3 (1 file).
5. Commit Fase 4 (6 file).
6. Jalankan `raliva:reset-transaksi --dry-run`.
7. Jalankan `raliva:reset-transaksi --force`.
8. Verifikasi count transaksi dan master.
9. `php artisan view:cache`.
10. Jalankan dua test terarah.
11. Laporkan hasil dan hash commit.
12. Tidak push.

---

## Lampiran — catatan audit tambahan

- `theme-head` di-include oleh:
  - `layouts/superadmin.blade.php`
  - `layouts/admin.blade.php`
  - `layouts/owner.blade.php`
  - `layouts/gudang.blade.php`
  - `layouts/produksi.blade.php`
- Customer layout tidak meng-include `theme-head`.
- `Owner/dashboard` dan `Produksi/dashboard` memakai `raliva-figure` untuk judul halaman mobile.
- `Admin/profil` memakai `raliva-figure` untuk role dan status.
- `Owner/paket-slot` memakai `raliva-figure` untuk nama paket di hero card.
- `Owner/pengajuan-toko` memakai `raliva-figure` untuk judul status.
- `Owner/data-toko`, `Admin/dashboard-operasional`, dan `Owner/dashboard` memakai `raliva-figure` untuk nama toko.
- `Produksi/dashboard` memakai `raliva-figure` untuk nama atelier.
- `Gudang/dashboard` punya mini bar sparkline hardcoded height, tetapi tidak menampilkan angka.
- `Produksi/dashboard` chart output memakai data QC nyata; target 40/280/720 adalah konstanta target.
- `Owner/pengiriman` harga kurir adalah copy statis, bukan tarif live.
- `orders` tidak punya `promotion_id`; konversi promo per kode tidak bisa dihitung tanpa schema baru.
- `slot_grants` adalah satu-satunya sumber kuota; `store_slot_subscriptions` hanya metadata.
