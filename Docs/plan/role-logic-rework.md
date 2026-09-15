# Rencana Rework Logic Per Role — Raliva Fashion

> Dibuat: 2026-09-14  
> Status: Planning — belum dieksekusi  
> Eksekusi: Batch 1+2+3 sekaligus, lalu Batch 4+5+6

---

## Daftar Isi

1. [Latar Belakang](#latar-belakang)
2. [Keputusan User](#keputusan-user)
3. [Pra-Syarat: Yang Harus Diperbaiki Sebelum Mulai](#pra-syarat)
4. [Batch 1: Sidebar + Alur Verifikasi → Produksi](#batch-1-sidebar--alur-verifikasi--produksi)
5. [Batch 2: Tambah Pesanan Online/Offline + Multi-Produk](#batch-2-tambah-pesanan-onlineoffline--multi-produk)
6. [Batch 3: Sistem Bahan Produksi + Form Input Bahan](#batch-3-sistem-bahan-produksi--form-input-bahan)
7. [Batch 4: Data Produk — Simplifikasi Approval + Kategori + Ukuran Custom](#batch-4-data-produk--simplifikasi-approval--kategori--ukuran-custom)
8. [Batch 5: Supplier + Data Transaksi](#batch-5-supplier--data-transaksi)
9. [Batch 6: Produksi Role](#batch-6-produksi-role)
10. [File Terpengaruh Total](#file-terpengaruh-total)
11. [Checklist Verifikasi](#checklist-verifikasi)

---

## Latar Belakang

Banyak logic antar role yang masih samar dan perlu diperbaiki:

- **Verifikasi pembayaran belum terhubung otomatis ke produksi** — setelah Admin verifikasi, order hanya jadi `dibayar` dan menunggu Admin manual klik "Proses". Tidak ada status `menunggu_produksi`.
- **Admin pesanan offline belum ada** — hanya online, limit 3 produk, tidak bisa tambah produk dinamis.
- **Sistem bahan produksi tidak ada** — controller `BahanProduksiController` (Produksi) hanya stub dengan mock data. Tidak ada model/migration untuk bahan.
- **Approval produk terlalu panjang** — alur saat ini: Admin → Owner → SuperAdmin. User ingin sederhana: Admin → SuperAdmin langsung.
- **Produksi belum bisa kelola bahan sendiri** — stub saja.
- **Data Pesanan masih ada tombol Edit & "Buat Ulang untuk Customer"** yang tidak perlu.
- **Sidebar urutan tidak sesuai** — Data Pesanan di atas Verifikasi Pembayaran, harus dibalik.
- **Pembayaran ditolak tidak terlihat di Data Pesanan** — hanya muncul di total Verifikasi Pembayaran, tidak ada badge di Data Pesanan.
- **Data Produk kebesaran** — perlu dikecilin tampilannya.
- **Stok Admin masih bisa edit** — harus read-only, stok menipis di paling atas.
- **Supplier punya 3 status** — harus hanya 2 (Aktif/Nonaktif).
- **Tidak ada halaman Data Transaksi untuk Admin** — perlu Pemasukan & Pengeluaran.

---

## Keputusan User

Berikut keputusan yang sudah dikonfirmasi:

| # | Topik | Keputusan |
|---|-------|-----------|
| 1 | Alur verifikasi → produksi | Verifikasi → Menunggu Produksi → Admin input bahan → Diproses |
| 2 | Sistem bahan | Buat tabel master `bahan_produksi` + stok (bukan teks saja) |
| 3 | Approval produk | Admin → SuperAdmin langsung (hapus Owner dari flow) |
| 4 | Offline payment | Tunai = langsung terverifikasi; Transfer = Admin upload bukti + verifikasi normal |
| 5 | Eksekusi | Batch 1+2+3 sekaligus, lalu Batch 4+5+6 |
| 6 | Tambah pesanan | Hapus "Mode Pesanan", ganti Online/Offline dengan dynamic add product (tanpa limit) |
| 7 | Stok Admin | Read-only, stok menipis di paling atas |
| 8 | Supplier | Hanya 2 status: Aktif & Nonaktif, tambah field stok |
| 9 | Data Transaksi | Pemasukan & Pengeluaran |
| 10 | Produksi | Bisa pilih bahan dari Gudang, tambah bahan sendiri, ubah status produksi sendiri |

---

## Pra-Syarat

Sebelum mulai task, pastikan hal-hal berikut sudah beres:

1. **Data Bank seeder** — sudah selesai (`DataBankSeeder` gabungan 3 seeder). Pastikan `migrate:fresh --seed` jalan tanpa error.
2. **Tabel `payment_method_accounts` sudah di-drop** — pastikan tidak ada referensi tersisa di mana pun.
3. **Model `PaymentMethodAccount.php` sudah dihapus** — pastikan tidak ada `use App\Models\PaymentMethodAccount` di controller/view/model mana pun.
4. **Backup database** — sebelum eksekusi, export SQL dulu supaya bisa restore kalau ada masalah.
5. **Git commit** — commit dulu perubahan Data Bank sebelum mulai batch ini, supaya bisa revert kalau perlu.
6. **Cek `orders.status` column type** — sudah dikonfirmasi: VARCHAR(30), bukan ENUM. Tidak perlu migration untuk tambah status baru.

---

## Batch 1: Sidebar + Alur Verifikasi → Produksi

### 1.1 Reorder Sidebar Admin

**File:** `resources/views/partials/sidebar-menu-admin.blade.php`

Ubah urutan item di group "Transaksi" (sekitar line 12-16).

**Sekarang:**
```php
['route' => 'admin.pesanan', 'icon' => 'shopping_cart', 'text' => 'Data Pesanan'],
['route' => 'admin.verifikasi-pembayaran', 'icon' => 'fact_check', 'text' => 'Verifikasi Pembayaran'],
['route' => 'admin.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana'],
```

**Menjadi:**
```php
['route' => 'admin.verifikasi-pembayaran', 'icon' => 'fact_check', 'text' => 'Verifikasi Pembayaran'],
['route' => 'admin.pesanan', 'icon' => 'shopping_cart', 'text' => 'Data Pesanan'],
['route' => 'admin.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana'],
```

### 1.2 Tambah Status `menunggu_produksi`

**Tidak perlu migration** — `orders.status` adalah VARCHAR(30), bukan ENUM. Cukup tambah konstanta di model.

**File:** `app/Models/Order.php`

Tambah konstanta:
```php
public const STATUS_MENUNGGU_PRODUKSI = 'menunggu_produksi';
```

### 1.3 Ubah VerifikasiPembayaranController::setujui()

**File:** `app/Http/Controllers/Admin/VerifikasiPembayaranController.php`

Di method `setujui()` (sekitar line 79-81), ganti target status order:

**Sebelum:**
```php
Order::where('checkout_id', $pembayaran->checkout_id)
    ->where('status', Order::STATUS_PENDING_PAYMENT)
    ->update(['status' => Order::STATUS_DIBAYAR]);
```

**Sesudah:**
```php
Order::where('checkout_id', $pembayaran->checkout_id)
    ->where('status', Order::STATUS_PENDING_PAYMENT)
    ->update(['status' => Order::STATUS_MENUNGGU_PRODUKSI]);
```

### 1.4 Update DataPesananController

**File:** `app/Http/Controllers/Admin/DataPesananController.php`

#### index() — tambah status ke array $statuses (sekitar line 22-29)

```php
$statuses = [
    Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
    Order::STATUS_MENUNGGU_PRODUKSI => 'Menunggu Produksi',  // TAMBAH
    Order::STATUS_DIPROSES => 'Diproses',
    Order::STATUS_DIKIRIM => 'Dikirim',
    Order::STATUS_SELESAI => 'Selesai',
    Order::STATUS_DIBATALKAN => 'Dibatalkan',
];
```

Update `orderByRaw CASE` (sekitar line 39):
```sql
CASE status 
  WHEN 'pending_payment' THEN 0 
  WHEN 'menunggu_produksi' THEN 1 
  WHEN 'diproses' THEN 2 
  ELSE 3 
END
```

#### proses() — ubah guard (sekitar line 68)

**Sebelum:**
```php
if ($pesanan->status !== Order::STATUS_DIBAYAR) {
```

**Sesudah:**
```php
if ($pesanan->status !== Order::STATUS_MENUNGGU_PRODUKSI) {
```

#### batalkan() — tambah menunggu_produksi (sekitar line 237)

**Sebelum:**
```php
if (! in_array($pesanan->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES], true)) {
```

**Sesudah:**
```php
if (! in_array($pesanan->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_MENUNGGU_PRODUKSI, Order::STATUS_DIPROSES], true)) {
```

### 1.5 Badge "Pembayaran Ditolak" di Data Pesanan

**File:** `resources/views/Admin/pesanan/index.blade.php`

Di table row (sekitar line 101, setelah badge status), tambah badge kalika payment ditolak:

```blade
@if ($pesanan->checkout?->payment?->status === \App\Models\Payment::STATUS_DITOLAK)
    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">Bayar Ditolak</span>
@endif
```

### 1.6 Hapus Tombol Edit di Data Pesanan

**File:** `resources/views/Admin/pesanan/index.blade.php`

Hapus button Edit (sekitar line 109-110):
```blade
@if (in_array($pesanan->status, [...STATUS_PENDING_PAYMENT, STATUS_DIBAYAR...], true) && $pesanan->shipments->isEmpty() && ! $pesanan->checkout?->payment)
    <button type="button" data-modal-open="modal-edit-{{ $pesanan->order_id }}" ...>Edit</button>
@endif
```

Hapus modal edit-items (sekitar line 177-236).

### 1.7 Hapus "Buat Ulang untuk Customer" di Modal Detail

**File:** `resources/views/Admin/pesanan/index.blade.php`

Hapus form "Buat Ulang untuk Customer" di modal detail (sekitar line 169-173):
```blade
<form method="POST" action="{{ route('admin.pesanan.store') }}">
    @csrf
    <input type="hidden" name="order_id" value="{{ $pesanan->order_id }}" />
    <button type="submit" ...>Buat Ulang untuk Customer</button>
</form>
```

Ganti dengan tombol "Tutup" saja.

### 1.8 Update View: Tombol Proses untuk `menunggu_produksi`

**File:** `resources/views/Admin/pesanan/index.blade.php`

Ubah kondisi tombol Proses (sekitar line 103):

**Sebelum:**
```blade
@if ($pesanan->status === \App\Models\Order::STATUS_DIBAYAR)
```

**Sesudah:**
```blade
@if ($pesanan->status === \App\Models\Order::STATUS_MENUNGGU_PRODUKSI)
```

Ubah kondisi tombol Batalkan (sekitar line 106):

**Sebelum:**
```blade
@if (in_array($pesanan->status, [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES], true))
```

**Sesudah:**
```blade
@if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIPROSES], true))
```

---

## Batch 2: Tambah Pesanan Online/Offline + Multi-Produk

### 2.1 Rewrite Form Tambah Pesanan

**File:** `resources/views/Admin/pesanan/index.blade.php`

Hapus modal lama `modal-tambah-pesanan` (sekitar line 282-354). Ganti dengan form baru.

**Struktur form baru:**

```html
<form method="POST" action="{{ route('admin.pesanan.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Radio: Online / Offline -->
    <div class="grid grid-cols-2 gap-3">
        <label> Online
            <input type="radio" name="tipe_pesanan" value="online" checked />
        </label>
        <label> Offline
            <input type="radio" name="tipe_pesanan" value="offline" />
        </label>
    </div>

    <!-- Online: pilih customer dari data yang ada -->
    <div id="online-fields">
        <select name="user_id" required>
            <option value="">— Pilih Customer —</option>
            @foreach ($customers as $c)
                <option value="{{ $c->user_id }}">{{ $c->nama_lengkap }} ({{ $c->email }})</option>
            @endforeach
        </select>
    </div>

    <!-- Offline: input manual -->
    <div id="offline-fields" class="hidden">
        <input name="nama_penerima" placeholder="Nama Customer" required />
        <input name="nomor_telepon" placeholder="No. Telepon" required />
        <input name="email_pelanggan" placeholder="Email (opsional)" />
        <textarea name="alamat" placeholder="Alamat" required></textarea>
    </div>

    <!-- Dynamic Add Product (port dari beautycare pattern) -->
    <div id="item-container"></div>
    <button type="button" onclick="addItemRow()">+ Tambah Produk</button>

    <!-- Payment (untuk offline) -->
    <div id="payment-fields" class="hidden">
        <span>Metode Pembayaran</span>
        <div class="grid grid-cols-2 gap-3">
            <label> Tunai
                <input type="radio" name="metode_bayar" value="tunai" checked />
            </label>
            <label> Transfer
                <input type="radio" name="metode_bayar" value="transfer" />
            </label>
        </div>

        <!-- Transfer: pilih akun dari Data Bank + upload bukti -->
        <div id="transfer-fields" class="hidden">
            <select name="payment_account_id">
                @foreach ($paymentAccounts as $a)
                    <option value="{{ $a->platform_bank_account_id }}">{{ $a->nama }}</option>
                @endforeach
            </select>
            <input type="file" name="bukti" accept="image/*" />
        </div>
    </div>
</form>
```

### 2.2 JavaScript Dynamic Add Product

**Pattern di-port dari beautycare** (`resources/views/kasir/transaksi/create.blade.php`):

```javascript
const variantsData = @json($variants);
let itemRowIndex = 0;

function getItemTemplate(index) {
    return `
    <div class="item-row ...">
        <input type="hidden" name="items[${index}][product_variant_id]" class="item-id-hidden" />
        <input type="hidden" name="items[${index}][nama_produk]" class="item-nama-hidden" />
        <input type="hidden" name="items[${index}][harga]" class="item-harga-hidden" value="0" />
        <input type="hidden" name="items[${index}][qty]" class="item-qty-hidden" value="1" />
        <input type="hidden" name="items[${index}][subtotal]" class="item-subtotal-hidden" value="0" />

        <select class="item-select" onchange="onItemChange(this)">
            <option value="">— Pilih Produk —</option>
            ${variantsData.map(v =>
                `<option value="${v.product_variant_id}"
                    data-nama="${v.product?.nama_produk ?? 'Produk'} — ${v.warna ?? ''} ${v.ukuran ?? ''}"
                    data-harga="${v.harga ?? 0}"
                    data-stok="${v.warehouseStocks?.sum('jumlah_stok') ?? 0}">
                    ${v.product?.nama_produk ?? 'Produk'} — ${v.warna ?? ''} ${v.ukuran ?? ''}
                    (stok ${v.warehouseStocks?.sum('jumlah_stok') ?? 0})
                    — Rp ${Number(v.harga ?? 0).toLocaleString('id-ID')}
                </option>`
            ).join('')}
        </select>

        <input type="number" value="1" min="1" class="item-qty"
            oninput="onQtyChange(this)" />

        <span class="item-subtotal-display">Rp 0</span>

        <button type="button" onclick="removeItemRow(this)">Hapus</button>
    </div>`;
}

function addItemRow() {
    const container = document.getElementById('item-container');
    const idx = itemRowIndex++;
    container.insertAdjacentHTML('beforeend', getItemTemplate(idx));
}

function removeItemRow(btn) {
    btn.closest('.item-row').remove();
    recalculateTotal();
}

function onItemChange(select) {
    const row = select.closest('.item-row');
    const option = select.options[select.selectedIndex];
    if (option && option.value) {
        const harga = parseFloat(option.dataset.harga) || 0;
        const stok = parseInt(option.dataset.stok) || 0;
        row.querySelector('.item-id-hidden').value = option.value;
        row.querySelector('.item-nama-hidden').value = option.dataset.nama;
        row.querySelector('.item-harga-hidden').value = harga;
        row.dataset.stok = stok;
    } else {
        row.querySelector('.item-id-hidden').value = '';
        row.querySelector('.item-nama-hidden').value = '';
        row.querySelector('.item-harga-hidden').value = 0;
        delete row.dataset.stok;
    }
    onQtyChange(row.querySelector('.item-qty'));
}

function onQtyChange(input) {
    const row = input.closest('.item-row');
    const qty = parseInt(input.value) || 1;
    if (qty < 1) input.value = 1;
    const stok = parseInt(row.dataset.stok);
    if (stok && qty > stok) {
        alert('Stok tidak mencukupi! Stok tersedia: ' + stok);
        input.value = stok;
    }
    const harga = parseFloat(row.querySelector('.item-harga-hidden').value) || 0;
    const subtotal = parseInt(input.value) * harga;
    row.querySelector('.item-qty-hidden').value = parseInt(input.value);
    row.querySelector('.item-subtotal-hidden').value = subtotal;
    row.querySelector('.item-subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    recalculateTotal();
}

function recalculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-subtotal-hidden').forEach(el => {
        total += parseFloat(el.value) || 0;
    });
    // Update display
}
```

### 2.3 Update Controller store()

**File:** `app/Http/Controllers/Admin/DataPesananController.php`

Rewrite method `store()`:

```php
public function store(Request $request)
{
    $storeIds = AdminContext::assignedStoreIds();
    $storeId = $storeIds[0] ?? null;
    if (! $storeId) {
        return back()->with('toast', [
            'message' => 'Admin belum ditugaskan ke toko mana pun.',
            'icon' => 'gpp_maybe',
        ]);
    }

    $tipePesanan = $request->input('tipe_pesanan', 'online');

    // Validasi items (TANPA max:3!)
    $data = $request->validate([
        'items' => ['required', 'array', 'min:1'],  // HAPUS max:3
        'items.*.product_variant_id' => ['required', 'exists:product_variants,product_variant_id'],
        'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
        'tipe_pesanan' => ['required', 'in:online,offline'],
        // Online
        'user_id' => ['required_if:tipe_pesanan,online', 'exists:users,user_id'],
        // Offline
        'nama_penerima' => ['required_if:tipe_pesanan,offline', 'string', 'max:150'],
        'nomor_telepon' => ['required_if:tipe_pesanan,offline', 'string', 'max:30'],
        'email_pelanggan' => ['nullable', 'email', 'max:150'],
        'alamat' => ['required_if:tipe_pesanan,offline', 'string', 'max:500'],
        // Payment offline
        'metode_bayar' => ['required_if:tipe_pesanan,offline', 'in:tunai,transfer'],
        'payment_account_id' => ['required_if:metode_bayar,transfer', 'exists:platform_bank_accounts,platform_bank_account_id'],
        'bukti' => ['required_if:metode_bayar,transfer', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
    ]);

    // Filter item kosong
    $items = collect($data['items'])->filter(fn($r) => !empty($r['product_variant_id']))->values();
    if ($items->isEmpty()) {
        return back()->with('toast', ['message' => 'Pilih minimal 1 produk.', 'icon' => 'gpp_maybe']);
    }

    // Validasi variant milik store + cek stok
    foreach ($items as $item) {
        $variant = ProductVariant::with('product')->find($item['product_variant_id']);
        if (! $variant || $variant->product->store_id !== $storeId) {
            return back()->with('toast', ['message' => 'Varian tidak valid.', 'icon' => 'gpp_maybe']);
        }
        $stok = $variant->warehouseStocks->sum('jumlah_stok');
        if ($item['quantity'] > $stok) {
            return back()->with('toast', [
                'message' => "Stok {$variant->product->nama_produk} tidak cukup.",
                'icon' => 'gpp_maybe',
            ]);
        }
    }

    // Resolve customer
    $userId = null;
    $isOffline = $tipePesanan === 'offline';

    if ($isOffline) {
        // Offline: cari user by email, atau buat user baru role Customer
        if (!empty($data['email_pelanggan'])) {
            $existing = User::where('email', $data['email_pelanggan'])->first();
            if ($existing && $existing->role?->nama_role === Role::CUSTOMER) {
                $userId = $existing->user_id;
            } else {
                // Buat user baru
                $user = User::create([
                    'nama_lengkap' => $data['nama_penerima'],
                    'email' => $data['email_pelanggan'],
                    'password' => Hash::make('Raliva123'),
                    'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
                    'nomor_telepon' => $data['nomor_telepon'],
                    'status' => 'aktif',
                    'email_verified_at' => now(),
                ]);
                $userId = $user->user_id;
            }
        }
        // Kalau tidak ada email, user_id = null (guest checkout)
    } else {
        $userId = $data['user_id'];
    }

    // Hitung subtotal
    $subtotal = 0;
    $prepared = [];
    foreach ($items as $item) {
        $variant = ProductVariant::with('product')->find($item['product_variant_id']);
        $harga = $variant->harga;
        $rowSub = $harga * $item['quantity'];
        $subtotal += $rowSub;
        $prepared[] = [
            'variant' => $variant,
            'qty' => $item['quantity'],
            'harga' => $harga,
            'subtotal' => $rowSub,
        ];
    }

    // DB Transaction
    $newOrder = DB::transaction(function () use ($prepared, $subtotal, $userId, $storeId, $isOffline, $data, $request) {
        $checkout = Checkout::create([
            'user_id' => $userId,
            'email_pelanggan' => $data['email_pelanggan'] ?? null,
            'nama_penerima' => $data['nama_penerima'] ?? null,
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'subtotal' => $subtotal,
            'total_diskon' => 0,
            'total_pajak' => 0,
            'biaya_layanan' => 0,
            'total_ongkir' => 0,
            'grand_total' => $subtotal,
            'status' => Checkout::STATUS_PENDING,
        ]);

        $newOrder = Order::create([
            'store_id' => $storeId,
            'checkout_id' => $checkout->checkout_id,
            'nomor_order' => 'RLV-'.$storeId.'-'.strtoupper(substr(md5(uniqid()), 0, 6)),
            'subtotal' => $subtotal,
            'grand_total' => $subtotal,
            'status' => Order::STATUS_PENDING_PAYMENT,
        ]);

        foreach ($prepared as $p) {
            OrderItem::create([
                'order_id' => $newOrder->order_id,
                'product_variant_id' => $p['variant']->product_variant_id,
                'nama_produk_snapshot' => $p['variant']->product?->nama_produk ?? 'Produk',
                'harga_snapshot' => $p['harga'],
                'quantity' => $p['qty'],
                'subtotal' => $p['subtotal'],
                'diskon' => 0,
                'total' => $p['subtotal'],
            ]);
        }

        // Payment untuk offline
        if ($isOffline) {
            $metodeBayar = $data['metode_bayar'] ?? 'tunai';

            if ($metodeBayar === 'tunai') {
                // Tunai: langsung terverifikasi
                $payment = Payment::create([
                    'checkout_id' => $checkout->checkout_id,
                    'payment_method_id' => PaymentMethod::where('kode_metode', 'bank_transfer')->value('payment_method_id'),
                    'payment_account_id' => null,
                    'jumlah' => $subtotal,
                    'status' => Payment::STATUS_TERVERIFIKASI,
                    'batas_waktu' => now(),
                    'dibayar_pada' => now(),
                ]);

                PaymentVerification::create([
                    'payment_id' => $payment->payment_id,
                    'verifier_id' => ActivityLogger::resolveActorId(),
                    'status' => PaymentVerification::STATUS_DITERIMA,
                    'diverifikasi_pada' => now(),
                ]);

                $checkout->update(['status' => Checkout::STATUS_DIBAYAR]);
                $newOrder->update(['status' => Order::STATUS_MENUNGGU_PRODUKSI]);

            } else {
                // Transfer: upload bukti, menunggu verifikasi
                $fileName = 'bukti-'.$checkout->checkout_id.'-'.time().'.'.$request->file('bukti')->extension();
                $path = $request->file('bukti')->storeAs('payment_proofs', $fileName, 'public');

                $payment = Payment::create([
                    'checkout_id' => $checkout->checkout_id,
                    'payment_method_id' => PaymentMethod::where('kode_metode', 'bank_transfer')->value('payment_method_id'),
                    'payment_account_id' => $data['payment_account_id'],
                    'jumlah' => $subtotal,
                    'status' => Payment::STATUS_MENUNGGU_VERIFIKASI,
                    'batas_waktu' => now()->addMinutes(1440),
                ]);

                PaymentProof::create([
                    'payment_id' => $payment->payment_id,
                    'file_bukti' => $path,
                    'uploaded_at' => now(),
                ]);
            }
        }

        return $newOrder;
    });

    // Notifikasi
    Notification::fireSelf(Notification::TIPE_ORDER, 'Pesanan Manual Dibuat',
        sprintf('Pesanan %s dibuat.', $newOrder->nomor_order),
        ActivityLogger::resolveActorId(),
        route('admin.pesanan'));

    $msg = $isOffline && ($data['metode_bayar'] ?? '') === 'tunai'
        ? 'Pesanan offline dibuat (Tunai — Terverifikasi).'
        : 'Pesanan dibuat (Menunggu Pembayaran).';

    return back()->with('toast', ['message' => $msg, 'icon' => 'task_alt']);
}
```

### 2.4 Update index() — tambah data untuk form

**File:** `app/Http/Controllers/Admin/DataPesananController.php`

Di `index()`, tambah:

```php
$customers = \App\Models\User::whereHas('role', fn($q) => $q->where('nama_role', \App\Models\Role::CUSTOMER))
    ->orderByDesc('created_at')->limit(50)->get();

$paymentAccounts = \App\Models\PlatformBankAccount::where('status', \App\Models\PlatformBankAccount::STATUS_AKTIF)
    ->orderBy('urutan')->get();

return view('Admin.pesanan.index', [
    'orders'        => $orders,
    'statuses'      => $statuses,
    'activeStatus'  => $status,
    'variants'      => $variants,
    'recentOrders'  => $recentOrders,
    'customers'     => $customers,         // TAMBAH
    'paymentAccounts' => $paymentAccounts, // TAMBAH
]);
```

### 2.5 Hapus Mode Pesanan Lama

**File:** `resources/views/Admin/pesanan/index.blade.php`

Hapus CSS mode salin/baru (sekitar line 277-281):
```html
<style>
    #modal-tambah-pesanan #mode-salin-fields { display: none; }
    #modal-tambah-pesanan:has(input[name="mode"][value="salin"]:checked) #mode-salin-fields { display: block; }
    #modal-tambah-pesanan:has(input[name="mode"][value="salin"]:checked) #mode-baru-fields { display: none; }
</style>
```

Hapus juga method `updateItems()` di controller (opsional — bisa simpan dulu, tapi tidak dipanggil di view).

---

## Batch 3: Sistem Bahan Produksi + Form Input Bahan

### 3.1 Migration: Tabel `bahan_produksi`

**File baru:** `database/migrations/2026_09_15_000001_create_bahan_produksi_table.php`

```php
Schema::create('bahan_produksi', function (Blueprint $table) {
    $table->bigIncrements('bahan_id');
    $table->unsignedBigInteger('store_id');
    $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
    $table->string('nama_bahan', 150);
    $table->string('kategori', 50)->default('lainnya'); // kain, aksesoris, kemasan, lainnya
    $table->string('satuan', 20)->default('meter'); // meter, pcs, roll, kg
    $table->integer('stok')->default(0);
    $table->integer('stok_minimum')->default(0);
    $table->unsignedBigInteger('supplier_id')->nullable();
    $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
    $table->string('status', 20)->default('aktif');
    $table->timestamps();
});
```

### 3.2 Migration: Pivot `production_order_bahan`

**File baru:** `database/migrations/2026_09_15_000002_create_production_order_bahan_table.php`

```php
Schema::create('production_order_bahan', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('order_id');
    $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
    $table->unsignedBigInteger('bahan_id')->nullable();
    $table->foreign('bahan_id')->references('bahan_id')->on('bahan_produksi')->nullOnDelete();
    $table->string('nama_bahan', 150); // snapshot untuk bahan custom
    $table->decimal('jumlah', 10, 2);
    $table->string('satuan', 20);
    $table->text('catatan')->nullable();
    $table->unsignedBigInteger('created_by');
    $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
    $table->timestamps();
});
```

### 3.3 Model: BahanProduksi

**File baru:** `app/Models/BahanProduksi.php`

```php
class BahanProduksi extends Model
{
    protected $primaryKey = 'bahan_id';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'store_id', 'nama_bahan', 'kategori', 'satuan',
        'stok', 'stok_minimum', 'supplier_id', 'status',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
```

### 3.4 Model: ProductionOrderBahan

**File baru:** `app/Models/ProductionOrderBahan.php`

```php
class ProductionOrderBahan extends Model
{
    protected $fillable = [
        'order_id', 'bahan_id', 'nama_bahan', 'jumlah', 'satuan', 'catatan', 'created_by',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function bahan(): BelongsTo
    {
        return $this->belongsTo(BahanProduksi::class, 'bahan_id', 'bahan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
```

### 3.5 Admin: Form Input Bahan saat "Proses"

**File:** `app/Http/Controllers/Admin/DataPesananController.php`

Ubah `proses()`:

```php
public function proses(Request $request, Order $pesanan)
{
    if (! AdminContext::canAccessStore($pesanan->store_id)) {
        return back()->with('toast', [
            'message' => 'Pesanan ini di luar scope toko yang Anda tugaskan.',
            'icon' => 'gpp_maybe',
        ]);
    }
    if ($pesanan->status !== Order::STATUS_MENUNGGU_PRODUKSI) {
        return back()->with('toast', [
            'message' => 'Hanya pesanan menunggu produksi yang dapat diproses.',
            'icon' => 'gpp_maybe',
        ]);
    }

    // Validasi bahan
    $data = $request->validate([
        'bahan' => ['required', 'array', 'min:1'],
        'bahan.*.bahan_id' => ['nullable', 'exists:bahan_produksi,bahan_id'],
        'bahan.*.nama_bahan' => ['required', 'string', 'max:150'],
        'bahan.*.jumlah' => ['required', 'numeric', 'min:0.01'],
        'bahan.*.satuan' => ['required', 'string', 'max:20'],
        'bahan.*.catatan' => ['nullable', 'string', 'max:500'],
    ]);

    DB::transaction(function () use ($pesanan, $data) {
        // Simpan bahan
        foreach ($data['bahan'] as $bahan) {
            ProductionOrderBahan::create([
                'order_id' => $pesanan->order_id,
                'bahan_id' => $bahan['bahan_id'] ?? null,
                'nama_bahan' => $bahan['nama_bahan'],
                'jumlah' => $bahan['jumlah'],
                'satuan' => $bahan['satuan'],
                'catatan' => $bahan['catatan'] ?? null,
                'created_by' => ActivityLogger::resolveActorId(),
            ]);
        }

        // Update status order
        $pesanan->update(['status' => Order::STATUS_DIPROSES]);
    });

    ActivityLogger::log('admin.order.process', Order::class, $pesanan->order_id,
        ['status' => Order::STATUS_MENUNGGU_PRODUKSI],
        ['status' => Order::STATUS_DIPROSES],
        sprintf('Memproses pesanan %s dengan input bahan.', $pesanan->nomor_order));

    // Notifikasi ke Produksi
    NotificationService::sendToRole(
        Role::PRODUKSI,
        Notification::TIPE_SISTEM,
        'Pesanan Diproses',
        sprintf('Pesanan %s sedang diproses. Bahan telah diinput oleh Admin.', $pesanan->nomor_order),
        ActivityLogger::resolveActorId(),
        route('produksi.data-produksi')
    );

    // Notifikasi ke customer
    $this->notifyCustomer($pesanan, 'Pesanan Diproses',
        sprintf('Pesanan %s sedang diproses oleh toko.', $pesanan->nomor_order));

    return back()->with('toast', [
        'message' => "Pesanan {$pesanan->nomor_order} kini diproses.",
        'icon' => 'task_alt',
    ]);
}
```

### 3.6 View: Modal Form Bahan

**File:** `resources/views/Admin/pesanan/index.blade.php`

Ganti modal proses lama (confirm-only) dengan modal form bahan:

```blade
@if ($pesanan->status === \App\Models\Order::STATUS_MENUNGGU_PRODUKSI)
<div id="modal-proses-{{ $pesanan->order_id }}" data-modal class="...">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}" class="...">
        @csrf
        <h3>Input Bahan Produksi</h3>
        <p>Pesanan: {{ $pesanan->nomor_order }}</p>

        <!-- Dynamic bahan rows -->
        <div id="bahan-container-{{ $pesanan->order_id }}"></div>
        <button type="button" onclick="addBahanRow({{ $pesanan->order_id }})">
            + Tambah Bahan
        </button>

        <button type="submit">Proses Pesanan</button>
    </form>
</div>
@endif
```

### 3.7 JS Dynamic Bahan Rows

```javascript
function getBahanTemplate(orderId, index) {
    const bahanData = window['bahanData_' + orderId] || [];
    return `
    <div class="bahan-row ...">
        <select name="bahan[${index}][bahan_id]" onchange="onBahanChange(this)">
            <option value="">— Pilih Bahan (atau input manual) —</option>
            ${bahanData.map(b =>
                `<option value="${b.bahan_id}"
                    data-nama="${b.nama_bahan}"
                    data-satuan="${b.satuan}">
                    ${b.nama_bahan} (Stok: ${b.stok} ${b.satuan})
                </option>`
            ).join('')}
        </select>
        <input type="text" name="bahan[${index}][nama_bahan]"
            placeholder="Nama bahan (jika custom)" required />
        <input type="number" name="bahan[${index}][jumlah]"
            step="0.01" min="0.01" placeholder="Jumlah" required />
        <input type="text" name="bahan[${index}][satuan]"
            placeholder="Satuan" required />
        <input type="text" name="bahan[${index}][catatan]"
            placeholder="Catatan (opsional)" />
        <button type="button" onclick="removeBahanRow(this)">Hapus</button>
    </div>`;
}

function addBahanRow(orderId) {
    const container = document.getElementById('bahan-container-' + orderId);
    const idx = (container.children.length || 0);
    container.insertAdjacentHTML('beforeend', getBahanTemplate(orderId, idx));
}

function removeBahanRow(btn) {
    btn.closest('.bahan-row').remove();
}

function onBahanChange(select) {
    const row = select.closest('.bahan-row');
    const option = select.options[select.selectedIndex];
    if (option && option.value) {
        row.querySelector('input[name*="[nama_bahan]"]').value = option.dataset.nama;
        row.querySelector('input[name*="[satuan]"]').value = option.dataset.satuan;
    }
}
```

### 3.8 DataPesananController index() — pass bahanList

**File:** `app/Http/Controllers/Admin/DataPesananController.php`

Di `index()`, tambah:

```php
$bahanList = \App\Models\BahanProduksi::where('store_id', $storeId)
    ->where('status', \App\Models\BahanProduksi::STATUS_AKTIF)
    ->orderBy('nama_bahan')
    ->get();
```

Pass ke view. Di view, untuk setiap modal proses:

```blade
<script>
window.bahanData_{{ $pesanan->order_id }} = @json($bahanList);
</script>
```

### 3.9 Notifikasi ke Produksi saat Verifikasi

**File:** `app/Http/Controllers/Admin/VerifikasiPembayaranController.php`

Di `setujui()`, setelah update order status, tambah:

```php
// Notifikasi ke Produksi
\App\Services\NotificationService::sendToRole(
    \App\Models\Role::PRODUKSI,
    \App\Models\Notification::TIPE_SISTEM,
    'Pesanan Siap Diproduksi',
    sprintf('Pembayaran pesanan #%d telah diverifikasi. Menunggu input bahan dari Admin.', $pembayaran->checkout_id),
    ActivityLogger::resolveActorId(),
    route('produksi.data-produksi')
);
```

### 3.10 Produksi: BahanProduksiController (implement real)

**File:** `app/Http/Controllers/Produksi/BahanProduksiController.php`

```php
class BahanProduksiController extends Controller
{
    public function index()
    {
        $storeIds = \App\Models\StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $bahans = \App\Models\BahanProduksi::whereIn('store_id', $storeIds)
            ->with('supplier')
            ->orderBy('nama_bahan')
            ->paginate(15);

        $stats = [
            'total'    => \App\Models\BahanProduksi::whereIn('store_id', $storeIds)->count(),
            'aktif'    => \App\Models\BahanProduksi::whereIn('store_id', $storeIds)->where('status', 'aktif')->count(),
            'menipis'  => \App\Models\BahanProduksi::whereIn('store_id', $storeIds)
                ->where('status', 'aktif')
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count(),
        ];

        return view('Produksi.bahan-produksi.index', compact('bahans', 'stats'));
    }

    public function store(Request $request)
    {
        $storeIds = \App\Models\StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $data = $request->validate([
            'nama_bahan' => 'required|string|max:150',
            'kategori' => 'required|in:kain,aksesoris,kemasan,lainnya',
            'satuan' => 'required|string|max:20',
            'stok' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ]);

        $data['store_id'] = $storeIds[0] ?? null;
        $data['status'] = 'aktif';

        \App\Models\BahanProduksi::create($data);

        return back()->with('toast', [
            'message' => 'Bahan produksi berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }
}
```

### 3.11 Produksi: View bahan-produksi (real data)

**File:** `resources/views/Produksi/bahan-produksi/index.blade.php`

Rewrite: hapus mock data, tampilkan `$bahans` dari controller dengan tabel + form tambah bahan + stats cards.

### 3.12 Route untuk bahan produksi

**File:** `routes/web.php`

Tambah di produksi group:

```php
Route::post('/bahan-produksi', [\App\Http\Controllers\Produksi\BahanProduksiController::class, 'store'])->name('bahan-produksi.store');
```

Tambah di admin group (untuk kelola bahan):

```php
Route::get('/bahan-produksi', [\App\Http\Controllers\Admin\BahanProduksiController::class, 'index'])->name('bahan-produksi');
Route::post('/bahan-produksi', [\App\Http\Controllers\Admin\BahanProduksiController::class, 'store'])->name('bahan-produksi.store');
Route::post('/bahan-produksi/{bahan}/update', [\App\Http\Controllers\Admin\BahanProduksiController::class, 'update'])->name('bahan-produksi.update');
```

### 3.13 Admin: BahanProduksiController (CRUD)

**File baru:** `app/Http/Controllers/Admin/BahanProduksiController.php`

```php
class BahanProduksiController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $bahans = \App\Models\BahanProduksi::whereIn('store_id', $storeIds)
            ->with('supplier')
            ->orderBy('nama_bahan')
            ->paginate(15);
        return view('Admin.bahan-produksi.index', compact('bahans'));
    }

    public function store(Request $request) { /* CRUD create */ }
    public function update(Request $request, \App\Models\BahanProduksi $bahan) { /* CRUD update */ }
}
```

**File baru:** `resources/views/Admin/bahan-produksi/index.blade.php`

View dengan tabel bahan + form CRUD + filter kategori.

---

## Batch 4: Data Produk — Simplifikasi Approval + Kategori + Ukuran Custom

### 4.1 Simplifikasi Approval: Admin → SuperAdmin

**Perubahan:**
- Admin create → `status = pending` → notifikasi **SuperAdmin langsung** (hapus notif ke Owner)
- Hapus seting `owner_verified_at` dari flow Admin
- SuperAdmin tetap setujui/tolak seperti biasa

**File:**
- `app/Http/Controllers/Admin/DataProdukController.php` — hapus blok notifikasi ke Owner (sekitar line 70-80), ganti dengan notif ke SuperAdmin
- `app/Models/Product.php` — `owner_verified_at` tetap di fillable (untuk backward compat) tapi tidak di-set saat create

### 4.2 Admin Bisa Tambah Kategori

**File baru:** `app/Http/Controllers/Admin/KategoriController.php`

```php
class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ]);
        $data['status'] = 'aktif';
        \App\Models\Category::create($data);
        return back()->with('toast', ['message' => 'Kategori ditambahkan.', 'icon' => 'task_alt']);
    }
}
```

**Route:** `admin.kategori.store`

**View:** Di form produk, tambah tombol "Tambah Kategori Baru" (input text + submit) di samping select kategori.

### 4.3 Ukuran Custom

**Perubahan di form produk:**
- Hapus fixed chips `['XS','S','M','L','XL','XXL','All Size']`
- Ganti dengan:
  - Default chips (XS, S, M, L, XL, XXL, All Size) — masih bisa pilih
  - Button "Custom" → muncul input fields: Lingkaran Dada (LD), Panjang Baju (PB), Lebar Bahu (LB), Lingkar Tangan (LT), Panjang Lebar (PL)
- Disimpan sebagai string di `product_variants.ukuran`

**File:** `resources/views/Admin/produk/index.blade.php` — rewrite bagian ukuran

### 4.4 Kecilin Tampilan Produk

**File:** `resources/views/Admin/produk/index.blade.php`

- Kecilin card size: `max-w-xs` atau grid 4-5 kolom (dari 3)
- Padding lebih kecil
- Foto tetap full resolution tapi container lebih kecil (`h-40` dari `h-60`)

### 4.5 Admin: Read-Only Stok + Stok Menipis di Atas

**File:** `app/Http/Controllers/Admin/StokController.php`

- Hapus method `update()` (read-only)
- Sort: stok menipis di atas

```php
$stocks = WarehouseStock::with([...])
    ->whereIn('warehouse_id', $warehouseIds)
    ->orderByRaw("CASE WHEN jumlah_stok <= stok_minimum THEN 0 ELSE 1 END")
    ->orderBy('jumlah_stok', 'asc')
    ->paginate(15);
```

**File:** `resources/views/Admin/stok/index.blade.php`

- Hapus form inline edit
- Ganti dengan badge status (Menipis/Aman) saja

---

## Batch 5: Supplier + Data Transaksi

### 5.1 Supplier: 2 Status (Aktif/Nonaktif)

**Perubahan:**
- Hapus status `verifikasi` dari supplier
- Hanya `aktif` dan `nonaktif`

**File:**
- `app/Http/Controllers/Admin/SupplierController.php` — validasi: `status => 'required|in:aktif,nonaktif'`
- `resources/views/Admin/supplier/supplier.blade.php` — hapus radio "verifikasi"

### 5.2 Supplier: Tambah Stok

**Migration:** `alter suppliers add stok integer default 0 after status`

**File:**
- `app/Models/Supplier.php` — tambah `stok` ke fillable
- `app/Http/Controllers/Admin/SupplierController.php` — tambah validasi `stok`
- View: tambah kolom stok di table + form

### 5.3 Data Transaksi: Pemasukan & Pengeluaran

**File baru:** `app/Http/Controllers/Admin/TransaksiController.php`

```php
class TransaksiController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();

        // Pemasukan: Order selesai + WalletTransaction pemasukan
        $pemasukan = \App\Models\WalletTransaction::whereHas('wallet', fn($q) =>
            $q->whereIn('store_id', $storeIds)
        )->whereIn('jenis_transaksi', [
            \App\Models\WalletTransaction::JENIS_PENJUALAN_MASUK,
            \App\Models\WalletTransaction::JENIS_KOMISI_MASUK,
            \App\Models\WalletTransaction::JENIS_PEMASUKAN,
        ])->orderByDesc('created_at')->paginate(10, ['*'], 'pemasukan_page');

        // Pengeluaran: Refund + StoreExpense + Withdrawal
        $pengeluaran = \App\Models\StoreExpense::whereIn('store_id', $storeIds)
            ->orderByDesc('tanggal')
            ->paginate(10, ['*'], 'pengeluaran_page');

        return view('Admin.transaksi.index', compact('pemasukan', 'pengeluaran'));
    }

    public function storePemasukan(Request $request) { /* ... */ }
    public function storePengeluaran(Request $request) { /* ... */ }
}
```

**File baru:** `resources/views/Admin/transaksi/index.blade.php`

View dengan:
- 2 tab: Pemasukan & Pengeluaran
- Form input di masing-masing tab
- Tabel list transaksi

**Route:**
```php
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::post('/transaksi/pemasukan', [TransaksiController::class, 'storePemasukan'])->name('transaksi.pemasukan');
Route::post('/transaksi/pengeluaran', [TransaksiController::class, 'storePengeluaran'])->name('transaksi.pengeluaran');
```

---

## Batch 6: Produksi Role

### 6.1 Data Produksi Muncul (Real Data)

**File:** `app/Http/Controllers/Produksi/DataProduksiController.php`

```php
class DataProduksiController extends Controller
{
    public function index()
    {
        $storeIds = \App\Models\StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        // Order dengan status diproses + bahan yang sudah diinput
        $orders = \App\Models\Order::whereIn('store_id', $storeIds)
            ->where('status', \App\Models\Order::STATUS_DIPROSES)
            ->with(['items.productVariant.product', 'bahanList.bahan', 'checkout'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('Produksi.data-produksi.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Produksi ubah status: diproses → selesai (atau menunggu_qc)
        $data = $request->validate([
            'status' => 'required|in:selesai,menunggu_qc',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($order->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', ['message' => 'Status tidak valid.', 'icon' => 'gpp_maybe']);
        }

        $order->update(['status' => $data['status']]);

        // Notifikasi ke Admin
        // ...

        return back()->with('toast', ['message' => 'Status produksi diperbarui.', 'icon' => 'task_alt']);
    }
}
```

**File:** `resources/views/Produksi/data-produksi/index.blade.php`

Rewrite: tampilkan `$orders` dengan bahan yang sudah diinput, tombol ubah status.

### 6.2 Produksi Pilih Bahan dari Gudang

Sudah di Batch 3:
- `BahanProduksiController` (Produksi) — list bahan dari store
- Produksi bisa tambah bahan sendiri
- Gudang kelola stok bahan

### 6.3 Notifikasi ke Produksi

Sudah di Batch 3:
- Saat Admin verifikasi pembayaran → notif ke Produksi (section 3.9)
- Saat Admin input bahan → notif ke Produksi (section 3.5)

### 6.4 Produksi Ubah Status Sendiri

Sudah di section 6.1:
- Route: `produksi.data-produksi.status` (POST)
- Controller: `DataProduksiController@updateStatus`
- Produksi bisa ubah: `diproses` → `selesai` atau `menunggu_qc`

---

## File Terpengaruh Total

### Batch 1+2+3 (Eksekusi Sekaligus)

| Batch | File | Aksi |
|-------|------|------|
| 1 | `resources/views/partials/sidebar-menu-admin.blade.php` | Edit: reorder menu |
| 1 | `app/Models/Order.php` | Edit: tambah konstanta `STATUS_MENUNGGU_PRODUKSI` |
| 1 | `app/Http/Controllers/Admin/VerifikasiPembayaranController.php` | Edit: `setujui()` ubah status + notif Produksi |
| 1 | `app/Http/Controllers/Admin/DataPesananController.php` | Edit: `index()`, `proses()`, `batalkan()` |
| 1 | `resources/views/Admin/pesanan/index.blade.php` | Edit: hapus Edit, hapus Buat Ulang, badge, tombol Proses |
| 2 | `app/Http/Controllers/Admin/DataPesananController.php` | Edit: rewrite `store()` |
| 2 | `resources/views/Admin/pesanan/index.blade.php` | Edit: rewrite modal tambah pesanan |
| 3 | `database/migrations/2026_09_15_000001_create_bahan_produksi_table.php` | **Baru** |
| 3 | `database/migrations/2026_09_15_000002_create_production_order_bahan_table.php` | **Baru** |
| 3 | `app/Models/BahanProduksi.php` | **Baru** |
| 3 | `app/Models/ProductionOrderBahan.php` | **Baru** |
| 3 | `app/Http/Controllers/Admin/DataPesananController.php` | Edit: `proses()` dengan form bahan |
| 3 | `resources/views/Admin/pesanan/index.blade.php` | Edit: modal form bahan + JS |
| 3 | `app/Http/Controllers/Produksi/BahanProduksiController.php` | Edit: implement real |
| 3 | `resources/views/Produksi/bahan-produksi/index.blade.php` | Edit: real data |
| 3 | `app/Http/Controllers/Admin/BahanProduksiController.php` | **Baru** |
| 3 | `resources/views/Admin/bahan-produksi/index.blade.php` | **Baru** |
| 3 | `routes/web.php` | Edit: route bahan (admin + produksi) |
| 3 | `app/Models/Order.php` | Edit: tambah relasi `bahanList()` |

### Batch 4+5+6 (Eksekusi Nanti)

| Batch | File | Aksi |
|-------|------|------|
| 4 | `app/Http/Controllers/Admin/DataProdukController.php` | Edit: hapus notif Owner |
| 4 | `app/Http/Controllers/Admin/KategoriController.php` | **Baru** |
| 4 | `resources/views/Admin/produk/index.blade.php` | Edit: form + tampilan + ukuran custom |
| 4 | `app/Http/Controllers/Admin/StokController.php` | Edit: read-only + sort menipis |
| 4 | `resources/views/Admin/stok/index.blade.php` | Edit: hapus form edit |
| 4 | `routes/web.php` | Edit: route kategori admin |
| 5 | `database/migrations/2026_09_15_000003_add_stok_to_suppliers_table.php` | **Baru** |
| 5 | `app/Models/Supplier.php` | Edit: tambah `stok` ke fillable |
| 5 | `app/Http/Controllers/Admin/SupplierController.php` | Edit: 2 status + stok |
| 5 | `resources/views/Admin/supplier/supplier.blade.php` | Edit |
| 5 | `app/Http/Controllers/Admin/TransaksiController.php` | **Baru** |
| 5 | `resources/views/Admin/transaksi/index.blade.php` | **Baru** |
| 5 | `routes/web.php` | Edit: route transaksi |
| 6 | `app/Http/Controllers/Produksi/DataProduksiController.php` | Edit: real data + `updateStatus()` |
| 6 | `resources/views/Produksi/data-produksi/index.blade.php` | Edit: real data |
| 6 | `routes/web.php` | Edit: route produksi status |

---

## Checklist Verifikasi

### Batch 1+2+3

- [ ] `php -l` semua file berubah — no syntax errors
- [ ] `php artisan migrate` — 2 migration baru sukses
- [ ] `php artisan migrate:fresh --seed` — sukses tanpa error
- [ ] Sidebar: Verifikasi Pembayaran di atas Data Pesanan
- [ ] Verifikasi pembayaran → order status `menunggu_produksi` (bukan `dibayar`)
- [ ] Data Pesanan: tidak ada tombol Edit
- [ ] Data Pesanan: tidak ada "Buat Ulang untuk Customer"
- [ ] Data Pesanan: badge "Bayar Ditolak" muncul untuk payment ditolak
- [ ] Tambah pesanan online: pilih customer + dynamic add product (tanpa limit 3)
- [ ] Tambah pesanan offline: input nama, telp, alamat + dynamic add product
- [ ] Offline tunai: langsung `terverifikasi` + order `menunggu_produksi`
- [ ] Offline transfer: upload bukti + order `pending_payment`
- [ ] Admin klik "Proses" → muncul modal form bahan (bukan confirm-only)
- [ ] Submit form bahan → order `diproses` + bahan tersimpan di `production_order_bahan`
- [ ] Tabel `bahan_produksi` ada di database
- [ ] Tabel `production_order_bahan` ada di database
- [ ] Produksi: halaman bahan-produksi menampilkan real data (bukan mock)
- [ ] Produksi: bisa tambah bahan sendiri
- [ ] Notifikasi ke Produksi saat pembayaran diverifikasi
- [ ] Notifikasi ke Produksi saat Admin input bahan

### Batch 4+5+6

- [ ] Approval produk: Admin → SuperAdmin (tidak lewat Owner)
- [ ] Admin bisa tambah kategori baru di form produk
- [ ] Form produk: ukuran custom (LD, PB, LB, LT, PL) dengan button custom
- [ ] Tampilan produk lebih kecil
- [ ] Stok Admin: read-only, stok menipis di paling atas
- [ ] Supplier: hanya 2 status (Aktif/Nonaktif)
- [ ] Supplier: ada field stok
- [ ] Halaman Data Transaksi: Pemasukan & Pengeluaran
- [ ] Produksi: Data Produksi menampilkan real data (order diproses + bahan)
- [ ] Produksi: bisa ubah status (diproses → selesai)
