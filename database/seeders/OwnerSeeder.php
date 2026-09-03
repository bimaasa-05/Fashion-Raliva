<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Checkout;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreDocument;
use App\Models\StoreExpense;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerSeeder extends Seeder
{
    /**
     * Seed data lengkap untuk role Owner (store + produk, promo, pelanggan,
     * pesanan, ulasan, keuangan, karyawan, laporan).
     *
     * Dijalankan terpisah agar `php artisan migrate:fresh --seed` tetap
     * menghasilkan owner TANPA toko (default), dan data demo ini bersifat
     * opt-in:
     *   php artisan db:seed --class=OwnerSeeder
     */
    public function run(): void
    {
        // Bersihkan data demo lama agar seeding deterministik (idempoten).
        $old = Store::where('nama_toko', 'Raliva Atelier Jakarta')->first();
        if ($old) {
            // Reset data toko lama: matikan FK check agar semua child (varian,
            // stok, warehouse, pesanan, dll) bisa dihapus tanpa violasi constraint.
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Wallet::where('store_id', $old->store_id)->delete();
            StoreExpense::where('store_id', $old->store_id)->delete();
            StoreDocument::where('store_id', $old->store_id)->delete();
            Promotion::where('store_id', $old->store_id)->delete();
            Review::where('store_id', $old->store_id)->delete();
            Complaint::where('store_id', $old->store_id)->delete();
            // Bersihkan produk lama (FK check sudah dimatikan di awal blok).
            ProductVariant::whereIn('product_id', Product::where('store_id', $old->store_id)->pluck('product_id'))->delete();
            ProductionOrderItem::whereIn('production_order_id', ProductionOrder::where('store_id', $old->store_id)->pluck('production_order_id'))->delete();
            ProductionOrder::where('store_id', $old->store_id)->delete();
            OrderItem::whereIn('order_id', Order::where('store_id', $old->store_id)->pluck('order_id'))->delete();
            Order::where('store_id', $old->store_id)->delete();
            Product::where('store_id', $old->store_id)->delete();
            StoreStaff::where('store_id', $old->store_id)->delete();
            Warehouse::where('store_id', $old->store_id)->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $old->delete();
        }

        $owner = User::where('email', 'o@gmail.com')->firstOrFail();

        // ---- TOKO ----
        $store = Store::updateOrCreate(
            ['nama_toko' => 'Raliva Atelier Jakarta'],
            [
                'owner_id' => $owner->user_id,
                'deskripsi' => 'Atelier fashion premium Raliva di Jakarta — bahan lokal, jahitan tangan.',
                'alamat' => 'Jl. Kemang Raya No. 21, Jakarta Selatan',
                'nomor_telepon' => '0215551234',
                'status' => Store::STATUS_AKTIF,
                'operational_hours' => $this->defaultOperationalHours(),
            ]
        );

        // ---- ADMIN & STAFF TOKO ----
        $admin = User::where('email', 'admin@raliva.test')->first();
        if ($admin) {
            StoreStaff::updateOrCreate(
                ['user_id' => $admin->user_id, 'store_id' => $store->store_id],
                ['tanggal_penugasan' => now()->subMonths(4), 'status' => 'aktif']
            );
        }
        // staff tambahan (produksi & gudang) untuk halaman Karyawan
        $extraStaff = [
            ['email' => 'produksi@raliva.test', 'role' => 'produksi'],
            ['email' => 'gudang@raliva.test', 'role' => 'gudang'],
            ['email' => 'gudang2@raliva.test', 'role' => 'gudang'],
        ];
        foreach ($extraStaff as $s) {
            $u = User::where('email', $s['email'])->first();
            if ($u) {
                StoreStaff::updateOrCreate(
                    ['user_id' => $u->user_id, 'store_id' => $store->store_id],
                    ['tanggal_penugasan' => now()->subMonths(2), 'status' => 'aktif']
                );
            }
        }

        // ---- GUDANG ----
        $warehouse = Warehouse::updateOrCreate(
            ['nama_gudang' => 'Gudang Utama Bandung', 'store_id' => $store->store_id],
            [
                'alamat' => 'Jl. Asia Afrika No. 20, Bandung',
                'nomor_telepon' => '0225551234',
                'status' => Warehouse::STATUS_AKTIF,
            ]
        );

        // ---- KATEGORI (anak) ----
        $categories = Category::whereNotNull('parent_id')->where('status', 'aktif')->get()->keyBy('nama_kategori');
        $cat = fn (string $name) => ($categories[$name] ?? $categories->first())->category_id;

        // ---- PRODUK LENGKAP (25 produk, varian, status sebar untuk demo approval) ----
        $seed = [
            ['Trench Coat Signature', 'Jaket & Hoodie', 'KEM', [['S', 8], ['M', 12], ['L', 6]], 420000, 750000, Product::STATUS_AKTIF],
            ['Oversized Linen Shirt', 'Kemeja', 'KMS', [['S', 20], ['M', 24], ['L', 14]], 180000, 289000, Product::STATUS_AKTIF],
            ['Wide Leg Trousers', 'Celana', 'CLT', [['28', 10], ['30', 14], ['32', 8]], 175000, 295000, Product::STATUS_AKTIF],
            ['Midi Dress Linen', 'Dress', 'DRS', [['S', 16], ['M', 18], ['L', 12]], 220000, 389000, Product::STATUS_AKTIF],
            ['Knit Cardigan Rajut', 'Jaket & Hoodie', 'RDG', [['S', 10], ['M', 14], ['L', 9]], 185000, 299000, Product::STATUS_AKTIF],
            ['Silk Scarf Premium', 'Aksesoris', 'SYL', [['One Size', 30]], 95000, 185000, Product::STATUS_AKTIF],
            ['Basic T-Shirt Cotton', 'Kaos', 'KSL', [['S', 40], ['M', 50], ['L', 30]], 55000, 99000, Product::STATUS_AKTIF],
            ['Relaxed Blazer', 'Jaket & Hoodie', 'BLZ', [['M', 12], ['L', 10]], 320000, 549000, Product::STATUS_AKTIF],
            ['Pleated Skirt', 'Rok', 'RKT', [['S', 12], ['M', 10], ['L', 6]], 165000, 275000, Product::STATUS_AKTIF],
            ['Leather Belt', 'Ikat Pinggang', 'IKT', [['85-105 cm', 25], ['90-110 cm', 22]], 110000, 199000, Product::STATUS_AKTIF],
            ['Floral Kimono Outer', 'Jaket & Hoodie', 'KMO', [['S', 12], ['M', 15], ['L', 10]], 195000, 329000, Product::STATUS_PENDING],
            ['Cotton Oxford Shirt', 'Kemeja', 'KOX', [['M', 18], ['L', 16], ['XL', 12]], 165000, 259000, Product::STATUS_PENDING],
            ['High Waist Jeans', 'Celana', 'JNS', [['27', 14], ['29', 16], ['31', 10]], 185000, 319000, Product::STATUS_PENDING],
            ['Satin Slip Dress', 'Dress', 'SDR', [['S', 10], ['M', 12]], 210000, 369000, Product::STATUS_PENDING],
            ['Denim Jacket Classic', 'Jaket & Hoodie', 'DJN', [['M', 8], ['L', 9], ['XL', 6]], 280000, 459000, Product::STATUS_DITOLAK],
            ['Linen Short Pants', 'Celana', 'SHP', [['S', 15], ['M', 18]], 95000, 159000, Product::STATUS_DITOLAK],
            ['Striped Polo Shirt', 'Kaos', 'PLS', [['S', 22], ['M', 25], ['L', 20]], 75000, 129000, Product::STATUS_NONAKTIF],
            ['Wool Blend Coat', 'Jaket & Hoodie', 'WLC', [['M', 6], ['L', 5]], 480000, 799000, Product::STATUS_NONAKTIF],
            ['A-Line Mini Dress', 'Dress', 'AMD', [['S', 14], ['M', 16]], 175000, 299000, Product::STATUS_AKTIF],
            ['Cargo Pants Street', 'Celana', 'CRG', [['30', 12], ['32', 14], ['34', 10]], 165000, 279000, Product::STATUS_AKTIF],
            ['Hoodie Fleece Basic', 'Jaket & Hoodie', 'HOD', [['S', 20], ['M', 22], ['L', 18], ['XL', 15]], 135000, 229000, Product::STATUS_AKTIF],
            ['Pencil Skirt Office', 'Rok', 'PSK', [['S', 10], ['M', 12], ['L', 8]], 145000, 249000, Product::STATUS_AKTIF],
            ['Canvas Tote Bag', 'Aksesoris', 'CTB', [['One Size', 40]], 65000, 119000, Product::STATUS_AKTIF],
            ['Slim Fit Chinos', 'Celana', 'CHN', [['28', 12], ['30', 15], ['32', 13]], 155000, 269000, Product::STATUS_AKTIF],
            ['Embroidered Blouse', 'Kemeja', 'EMB', [['S', 12], ['M', 14]], 185000, 315000, Product::STATUS_AKTIF],
        ];

        $products = [];
        foreach ($seed as $idx => $row) {
            [$nama, $kategori, $prefix, $variants, $hpp, $harga, $status] = $row;
            $alasan = $status === Product::STATUS_DITOLAK ? 'Foto produk kurang jelas, mohon upload ulang dengan pencahayaan baik.' : null;
            $product = Product::updateOrCreate(
                ['store_id' => $store->store_id, 'nama_produk' => $nama],
                [
                    'category_id' => $cat($kategori),
                    'deskripsi' => "$nama — produksi lokal Raliva, bahan premium, jahitan tangan.",
                    'harga_dasar' => $harga,
                    'tipe_produk' => Product::TIPE_REGULAR,
                    'status' => $status,
                    'alasan_penolakan' => $alasan,
                ]
            );
            foreach ($variants as $vi => [$warna, $stok]) {
                ProductVariant::updateOrCreate(
                    ['product_id' => $product->product_id, 'sku' => $prefix.'-'.str_pad($idx + 1, 3, '0').'-'.($vi + 1)],
                    ['warna' => $warna, 'ukuran' => null, 'harga' => $harga, 'status' => 'aktif']
                );
            }
            $products[] = $product;
        }

        // ---- PROMO ----
        $promoSeed = [
            ['DISKON10', 'Diskon Lebaran 10%', 'persen', 10, 0, null, now()->subDays(5), now()->addDays(20), 'aktif'],
            ['CASHBACK25', 'Cashback Akhir Pekan', 'persen', 25, 50000, 100000, now()->addDays(2), now()->addDays(10), 'aktif'],
            ['PROMO50RB', 'Potongan Rp50.000', 'nominal', 50000, 0, null, now()->subDays(15), now()->subDays(2), 'nonaktif'],
        ];
        foreach ($promoSeed as $p) {
            Promotion::updateOrCreate(
                ['store_id' => $store->store_id, 'kode_promo' => $p[0]],
                [
                    'creator_id' => $owner->user_id,
                    'nama_promo' => $p[1],
                    'tipe_diskon' => $p[2],
                    'nilai_diskon' => $p[3],
                    'minimal_pembelian' => $p[4],
                    'maksimal_diskon' => $p[5],
                    'mulai_pada' => $p[6],
                    'berakhir_pada' => $p[7],
                    'status' => $p[8],
                ]
            );
        }

        // ---- PELANGGAN + PESANAN (agar podium & tabel pelanggan keisi) ----
        $customerNames = [
            ['Anindya Putri', 'anindya@raliva.test', 3],
            ['Bagus Pratama', 'bagus@raliva.test', 2],
            ['Citra Dewi', 'citra@raliva.test', 4],
            ['Dimas Saputra', 'dimas@raliva.test', 1],
            ['Eka Wulandari', 'eka@raliva.test', 2],
        ];
        $statuses = [Order::STATUS_SELESAI, Order::STATUS_DIKIRIM, Order::STATUS_DIPROSES, Order::STATUS_DIBAYAR, Order::STATUS_SELESAI, Order::STATUS_DIBATALKAN];
        $si = 0;
        foreach ($customerNames as $ci => [$cname, $cemail, $orderCount]) {
            $customer = User::updateOrCreate(
                ['email' => $cemail],
                [
                    'nama_lengkap' => $cname,
                    'password' => Hash::make('password'),
                    'role_id' => Role::where('nama_role', 'Customer')->value('role_id'),
                    'nomor_telepon' => '08120000'.str_pad($ci + 1, 3, '0'),
                    'status' => User::STATUS_AKTIF,
                ]
            );
            for ($o = 0; $o < $orderCount; $o++) {
                $variant = ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $store->store_id))->skip(($si) % 10)->first()
                    ?? ProductVariant::first();
                $qty = rand(1, 3);
                $harga = (float) ($variant->harga ?? 100000);
                $subtotal = $harga * $qty;
                $checkout = Checkout::create([
                    'user_id' => $customer->user_id,
                    'subtotal' => $subtotal,
                    'total_diskon' => 0,
                    'total_pajak' => 0,
                    'biaya_layanan' => 0,
                    'total_ongkir' => 0,
                    'grand_total' => $subtotal,
                    'status' => 'paid',
                ]);
                $order = Order::create([
                    'checkout_id' => $checkout->checkout_id,
                    'store_id' => $store->store_id,
                    'nomor_order' => 'RLV-'.$store->store_id.'-'.str_pad($si + 1, 4, '0').strtoupper(Str::random(2)),
                    'subtotal' => $subtotal,
                    'total_diskon' => 0,
                    'total_pajak' => 0,
                    'biaya_layanan' => 0,
                    'total_ongkir' => 0,
                    'grand_total' => $subtotal,
                    'status' => $statuses[$si % count($statuses)],
                    'created_at' => now()->subDays(rand(1, 40)),
                ]);
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'nama_produk_snapshot' => $variant->product->nama_produk ?? 'Produk',
                    'harga_snapshot' => $harga,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'diskon' => 0,
                    'total' => $subtotal,
                ]);
                $si++;
            }
        }

        // ---- ULASAN ----
        $reviewProducts = collect($products)->take(4);
        $reviewCustomers = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Customer'))->take(4)->get();
        $reviews = [
            ['Trench Coat-nya premium banget, jahitannya rapi!', 5],
            ['Ukurannya pas, bahannya adem.', 4],
            ['Pengiriman cepat, packing aman.', 5],
            ['Warna sesuai ekspektasi.', 4],
        ];
        foreach ($reviewProducts as $ri => $prod) {
            $cust = $reviewCustomers[$ri] ?? $reviewCustomers->first();
            if (! $cust) {
                continue;
            }
            $orderItem = OrderItem::whereHas('productVariant.product', fn ($q) => $q->where('product_id', $prod->product_id))->first();
            Review::updateOrCreate(
                ['store_id' => $store->store_id, 'product_id' => $prod->product_id, 'user_id' => $cust->user_id],
                [
                    'order_item_id' => $orderItem?->order_item_id,
                    'rating' => $reviews[$ri][1],
                    'ulasan' => $reviews[$ri][0],
                    'status' => Review::STATUS_AKTIF,
                ]
            );
        }

        // ---- WALLET + MUTASI ----
        $wallet = Wallet::updateOrCreate(
            ['store_id' => $store->store_id],
            ['saldo_tersedia' => 12500000, 'saldo_tertahan' => 2300000]
        );
        $mutations = [
            ['penjualan_masuk', 8000000, 'Penjualan order RLV-0001'],
            ['penjualan_masuk', 4500000, 'Penjualan order RLV-0003'],
            ['penjualan_masuk', 3000000, 'Penjualan order RLV-0005'],
            ['withdrawal', 3000000, 'Pencairan WD-0090'],
            ['penyesuaian', 0, 'Penyesuaian saldo'],
        ];
        $running = 0;
        foreach ($mutations as $m) {
            $masuk = in_array($m[0], [WalletTransaction::JENIS_PENJUALAN_MASUK, WalletTransaction::JENIS_KOMISI_MASUK]);
            $running += $masuk ? $m[1] : -$m[1];
            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => $m[0],
                'jumlah' => $m[1],
                'saldo_sebelum' => 0,
                'saldo_sesudah' => $running,
                'keterangan' => $m[2],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
        $wallet->update(['saldo_tersedia' => $running > 0 ? $running : $wallet->saldo_tersedia]);

        // ---- PENGELUARAN TOKO ----
        $expenses = [
            ['Listrik Toko', 'Operasional', 850000],
            ['Gaji Karyawan', 'Operasional', 5000000],
            ['Bahan Baku Kain', 'Produksi', 3200000],
            ['Iklan Instagram', 'Marketing', 1200000],
        ];
        foreach ($expenses as $e) {
            StoreExpense::create([
                'store_id' => $store->store_id,
                'nama' => $e[0],
                'kategori' => $e[1],
                'nominal' => $e[2],
                'tanggal' => now()->subDays(rand(1, 25)),
            ]);
        }

        // ---- DOKUMEN TOKO ----
        foreach (['ktp', 'npwp', 'foto_depan', 'siu'] as $jenis) {
            StoreDocument::updateOrCreate(
                ['store_id' => $store->store_id, 'jenis' => $jenis],
                ['path' => 'store-documents/'.$store->store_id.'/'.$jenis.'.pdf', 'status' => 'terverifikasi', 'catatan' => null]
            );
        }

        // ---- KOMPLAIN (demo) ----
        $custForComplaint = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Customer'))->first();
        $orderForComplaint = Order::where('store_id', $store->store_id)->first();
        $complaintsSeed = [
            ['Barang rusak', 'Kemeja item diterima sobek di bagian lengan.', 'baru'],
            ['Salah kirim warna', 'Pesanan warna navy tapi datang black.', 'diproses'],
            ['Pengiriman terlambat', 'Estimasi 3 hari tapi 7 hari baru sampai.', 'selesai'],
        ];
        foreach ($complaintsSeed as $ci => $c) {
            Complaint::create([
                'user_id' => $custForComplaint?->user_id,
                'order_id' => $orderForComplaint?->order_id,
                'store_id' => $store->store_id,
                'kategori' => $c[0],
                'subjek' => $c[0],
                'deskripsi' => $c[1],
                'status' => $c[2],
                'dibuat_pada' => now()->subDays($ci + 1),
                'diselesaikan_pada' => $c[2] === 'selesai' ? now()->subDays(1) : null,
            ]);
        }

        // ---- PERMINTAAN PRODUKSI (demo) ----
        $wh = Warehouse::where('store_id', $store->store_id)->first();
        $variantDemo = ProductVariant::whereIn('product_id', Product::where('store_id', $store->store_id)->pluck('product_id'))->first();
        $poSeed = [
            ['PO-001', 'tinggi', 'diproses'],
            ['PO-002', 'sedang', 'selesai'],
        ];
        foreach ($poSeed as $pi => $p) {
            $po = ProductionOrder::create([
                'store_id' => $store->store_id,
                'requested_by' => $owner->user_id,
                'assigned_to' => null,
                'target_warehouse_id' => $wh?->warehouse_id,
                'nomor_produksi' => $p[0],
                'prioritas' => $p[1],
                'status' => $p[2],
                'catatan' => 'Restock periode lebaran.',
                'dimulai_pada' => $p[2] === 'selesai' ? now()->subDays(10) : null,
                'selesai_pada' => $p[2] === 'selesai' ? now()->subDays(2) : null,
            ]);
            if ($variantDemo) {
                ProductionOrderItem::create([
                    'production_order_id' => $po->production_order_id,
                    'product_variant_id' => $variantDemo->product_variant_id,
                    'jumlah_diminta' => 25,
                ]);
            }
        }

        $this->command?->info('OwnerSeeder selesai: toko #'.$store->store_id.', '.Product::where('store_id', $store->store_id)->count().' produk, '.Order::where('store_id', $store->store_id)->count().' pesanan, '.Promotion::where('store_id', $store->store_id)->count().' promo.');
    }

    private function defaultOperationalHours(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $hours = [];
        foreach ($days as $day) {
            $isWeekend = $day === 'Minggu';
            $hours[$day] = [
                'buka' => $isWeekend ? false : true,
                'mulai' => $isWeekend ? null : '09:00',
                'selesai' => $isWeekend ? null : '21:00',
            ];
        }

        return $hours;
    }
}
