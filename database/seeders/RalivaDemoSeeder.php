<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Store;
use App\Models\StoreBankAccount;
use App\Models\StoreDocument;
use App\Models\StoreExpense;
use App\Models\StoreStaff;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Warehouse;
use App\Models\WarehouseStaff;
use App\Models\WarehouseStock;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RalivaDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ════════════════════════════════════════════════════════════════
        //  CLEANUP DATA LAMA (idempoten)
        // ════════════════════════════════════════════════════════════════
        Notification::query()->delete();
        ActivityLog::query()->delete();
        ComplaintMessage::query()->delete();
        Complaint::query()->delete();
        Review::query()->delete();
        Promotion::query()->delete();
        OrderItem::query()->delete();
        Order::query()->delete();
        Checkout::query()->delete();
        Refund::query()->delete();
        PaymentProof::query()->delete();
        Payment::query()->delete();
        Withdrawal::query()->delete();
        WalletTransaction::query()->delete();
        Wallet::query()->delete();
        StoreBankAccount::query()->delete();
        StoreExpense::query()->delete();
        StoreDocument::query()->delete();
        ProductionOrderItem::query()->delete();
        ProductionOrder::query()->delete();
        StockTransferItem::query()->delete();
        StockTransfer::query()->delete();
        StockMovement::query()->delete();
        WarehouseStock::query()->delete();
        WarehouseStaff::query()->delete();
        Warehouse::query()->delete();
        ProductVariant::query()->delete();
        Product::query()->delete();
        StoreStaff::query()->delete();
        Supplier::query()->delete();
        Store::query()->delete();

        // Nonaktifkan user lama dari user Seeder (kecuali akun utama seeder)
        User::where('role_id', '!=', Role::where('nama_role', Role::SUPER_ADMIN)->value('role_id'))
            ->whereNotIn('email', ['superadmin@raliva.test', 'sa@gmail.com', 'o@gmail.com', 'a@gmail.com', 'p@gmail.com', 'g@gmail.com', 'c@gmail.com', 'owner@raliva.test', 'admin@raliva.test', 'produksi@raliva.test', 'gudang@raliva.test'])
            ->update(['status' => User::STATUS_NONAKTIF]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ════════════════════════════════════════════════════════════════
        //  1. SUPER ADMIN (standalone — tidak ada kaitan dengan role lain)
        // ════════════════════════════════════════════════════════════════
        $sa = User::updateOrCreate(
            ['email' => 'superadmin@raliva.test'],
            [
                'nama_lengkap' => 'Super Admin Raliva',
                'password' => Hash::make('password'),
                'role_id' => Role::where('nama_role', Role::SUPER_ADMIN)->value('role_id'),
                'nomor_telepon' => '081100000000',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // ════════════════════════════════════════════════════════════════
        //  2. OWNER
        // ════════════════════════════════════════════════════════════════
        $owner = User::updateOrCreate(
            ['email' => 'owner@raliva.test'],
            [
                'nama_lengkap' => 'Bima Prasetya',
                'password' => Hash::make('password'),
                'role_id' => Role::where('nama_role', Role::OWNER)->value('role_id'),
                'nomor_telepon' => '081234567890',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // ════════════════════════════════════════════════════════════════
        //  3. STAFF TOKO (Admin, Produksi, Gudang)
        // ════════════════════════════════════════════════════════════════
        $staffUsers = [
            ['email' => 'admin@raliva.test', 'nama' => 'Sinta Maharani', 'telepon' => '081234567891', 'role' => 'Admin', 'tugas_bulan' => 4],
            ['email' => 'produksi@raliva.test', 'nama' => 'Rini Kusuma', 'telepon' => '081234567893', 'role' => 'Produksi', 'tugas_bulan' => 2],
            ['email' => 'gudang@raliva.test', 'nama' => 'Andi Pratama', 'telepon' => '081234567892', 'role' => 'Gudang', 'tugas_bulan' => 2],
            ['email' => 'gudang2@raliva.test', 'nama' => 'Doni Santoso', 'telepon' => '081234567895', 'role' => 'Gudang', 'tugas_bulan' => 1],
            ['email' => 'gudang3@raliva.test', 'nama' => 'Eka Prasetyo', 'telepon' => '081234567896', 'role' => 'Gudang', 'tugas_bulan' => 1],
            ['email' => 'produksi2@raliva.test', 'nama' => 'Siti Rahayu', 'telepon' => '081234567897', 'role' => 'Produksi', 'tugas_bulan' => 1],
        ];

        $staffModels = [];
        foreach ($staffUsers as $s) {
            $staffModels[$s['email']] = User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'nama_lengkap' => $s['nama'],
                    'password' => Hash::make('password'),
                    'role_id' => Role::where('nama_role', $s['role'])->value('role_id'),
                    'nomor_telepon' => $s['telepon'],
                    'email_verified_at' => now(),
                    'status' => User::STATUS_AKTIF,
                ]
            );
        }

        // ════════════════════════════════════════════════════════════════
        //  4. CUSTOMER
        // ════════════════════════════════════════════════════════════════
        $customerData = [
            ['email' => 'customer@raliva.test', 'nama' => 'Jane Doe', 'telepon' => '081234567894'],
            ['email' => 'customer2@raliva.test', 'nama' => 'Budi Santoso', 'telepon' => '081234567898'],
            ['email' => 'customer3@raliva.test', 'nama' => 'Dewi Lestari', 'telepon' => '081234567899'],
            ['email' => 'anindya@raliva.test', 'nama' => 'Anindya Putri', 'telepon' => '081200000001'],
            ['email' => 'bagus@raliva.test', 'nama' => 'Bagus Pratama', 'telepon' => '081200000002'],
            ['email' => 'citra@raliva.test', 'nama' => 'Citra Dewi', 'telepon' => '081200000003'],
            ['email' => 'dimas@raliva.test', 'nama' => 'Dimas Saputra', 'telepon' => '081200000004'],
            ['email' => 'eka@raliva.test', 'nama' => 'Eka Wulandari', 'telepon' => '081200000005'],
        ];

        $customers = [];
        foreach ($customerData as $c) {
            $customers[$c['email']] = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'nama_lengkap' => $c['nama'],
                    'password' => Hash::make('password'),
                    'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
                    'nomor_telepon' => $c['telepon'],
                    'email_verified_at' => now(),
                    'status' => User::STATUS_AKTIF,
                ]
            );
        }

        // ════════════════════════════════════════════════════════════════
        //  5. TOKO
        // ════════════════════════════════════════════════════════════════
        $store = Store::updateOrCreate(
            ['nama_toko' => 'Raliva Atelier Jakarta'],
            [
                'owner_id' => $owner->user_id,
                'deskripsi' => 'Atelier fashion premium Raliva di Jakarta — bahan lokal, jahitan tangan.',
                'alamat' => 'Jl. Kemang Raya No. 21, Jakarta Selatan',
                'nomor_telepon' => '0215551234',
                'status' => Store::STATUS_AKTIF,
            ]
        );

        // ════════════════════════════════════════════════════════════════
        //  6. STAFF PENUGASAN ke TOKO
        // ════════════════════════════════════════════════════════════════
        $storeStaffEmails = ['admin@raliva.test', 'produksi@raliva.test', 'produksi2@raliva.test', 'gudang@raliva.test', 'gudang2@raliva.test', 'gudang3@raliva.test'];
        foreach ($storeStaffEmails as $email) {
            if (isset($staffModels[$email])) {
                StoreStaff::updateOrCreate(
                    ['user_id' => $staffModels[$email]->user_id, 'store_id' => $store->store_id],
                    ['tanggal_penugasan' => now()->subMonths(4), 'status' => 'aktif']
                );
            }
        }

        // ════════════════════════════════════════════════════════════════
        //  7. GUDANG + STAF GUDANG
        // ════════════════════════════════════════════════════════════════
        $wh1 = Warehouse::updateOrCreate(
            ['nama_gudang' => 'Gudang Utama Bandung', 'store_id' => $store->store_id],
            [
                'alamat' => 'Jl. Soekarno Hatta No. 12, Bandung',
                'nomor_telepon' => '081234000001',
                'status' => Warehouse::STATUS_AKTIF,
            ]
        );
        $wh2 = Warehouse::updateOrCreate(
            ['nama_gudang' => 'Gudang Cabang Jakarta', 'store_id' => $store->store_id],
            [
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                'nomor_telepon' => '081234000002',
                'status' => Warehouse::STATUS_AKTIF,
            ]
        );

        $gudangEmails = ['gudang@raliva.test', 'gudang2@raliva.test', 'gudang3@raliva.test'];
        foreach ($gudangEmails as $email) {
            if (isset($staffModels[$email])) {
                foreach ([$wh1->warehouse_id, $wh2->warehouse_id] as $whId) {
                    WarehouseStaff::updateOrCreate(
                        ['warehouse_id' => $whId, 'user_id' => $staffModels[$email]->user_id],
                        ['tanggal_penugasan' => now()->subMonths(3), 'status' => 'aktif']
                    );
                }
            }
        }

        // ════════════════════════════════════════════════════════════════
        //  8. PRODUK + VARIAK + STOK + PERGERAKAN
        // ════════════════════════════════════════════════════════════════
        $categories = Category::whereNotNull('parent_id')->where('status', 'aktif')->get()->keyBy('nama_kategori');
        $cat = fn (string $name) => ($categories[$name] ?? $categories->first())->category_id;

        $productSeed = [
            ['Trench Coat Signature', 'Jaket & Hoodie', 'KEM', [['S', 8], ['M', 12], ['L', 6]], 420000, 750000],
            ['Oversized Linen Shirt', 'Kemeja', 'KMS', [['S', 20], ['M', 24], ['L', 14]], 180000, 289000],
            ['Wide Leg Trousers', 'Celana', 'CLT', [['28', 10], ['30', 14], ['L', 8]], 175000, 295000],
            ['Midi Dress Linen', 'Dress', 'DRS', [['S', 16], ['M', 18], ['L', 12]], 220000, 389000],
            ['Knit Cardigan Rajut', 'Jaket & Hoodie', 'RDG', [['S', 10], ['M', 14], ['L', 9]], 185000, 299000],
            ['Silk Scarf Premium', 'Aksesoris', 'SYL', [['One Size', 30]], 95000, 185000],
            ['Basic T-Shirt Cotton', 'Kaos', 'KSL', [['S', 40], ['M', 50], ['L', 30]], 55000, 99000],
            ['Relaxed Blazer', 'Jaket & Hoodie', 'BLZ', [['M', 12], ['L', 10]], 320000, 549000],
            ['Pleated Skirt', 'Rok', 'RKT', [['S', 12], ['M', 10], ['L', 6]], 165000, 275000],
            ['Leather Belt', 'Ikat Pinggang', 'IKT', [['85-105 cm', 25], ['90-110 cm', 22]], 110000, 199000],
        ];

        $products = [];
        $movements = [];
        $firstGudangId = isset($staffModels['gudang@raliva.test']) ? $staffModels['gudang@raliva.test']->user_id : 1;

        foreach ($productSeed as $idx => $row) {
            [$nama, $kategori, $prefix, $variants, $hpp, $harga] = $row;

            $product = Product::updateOrCreate(
                ['store_id' => $store->store_id, 'nama_produk' => $nama],
                [
                    'category_id' => $cat($kategori),
                    'deskripsi' => "$nama — produksi lokal Raliva, bahan premium.",
                    'harga_dasar' => $harga,
                    'tipe_produk' => Product::TIPE_REGULAR,
                    'status' => Product::STATUS_AKTIF,
                ]
            );

            foreach ($variants as $vi => [$warna, $stokAwal]) {
                $variant = ProductVariant::updateOrCreate(
                    ['product_id' => $product->product_id, 'sku' => $prefix.'-'.str_pad($idx + 1, 3, '0').'-'.($vi + 1)],
                    ['warna' => $warna, 'ukuran' => null, 'harga' => $harga, 'status' => 'aktif']
                );

                // Stok: 70% gudang 1, 30% gudang 2
                $stokWh1 = (int) round($stokAwal * 0.7);
                $stokWh2 = $stokAwal - $stokWh1;

                WarehouseStock::updateOrCreate(
                    ['warehouse_id' => $wh1->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                    ['jumlah_stok' => $stokWh1, 'jumlah_direservasi' => 0, 'stok_minimum' => 5]
                );
                if ($stokWh2 > 0) {
                    WarehouseStock::updateOrCreate(
                        ['warehouse_id' => $wh2->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                        ['jumlah_stok' => $stokWh2, 'jumlah_direservasi' => 0, 'stok_minimum' => 5]
                    );
                }

                // Pergerakan masuk (produksi)
                $movements[] = [
                    'warehouse_id' => $wh1->warehouse_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                    'jumlah' => $stokWh1,
                    'sumber_tipe' => StockMovement::SUMBER_PRODUCTION_RESULT,
                    'sumber_id' => null,
                    'alasan' => 'Stok awal produksi',
                    'dibuat_oleh' => $firstGudangId,
                    'created_at' => now()->subDays(20 + $idx),
                ];
                if ($stokWh2 > 0) {
                    $movements[] = [
                        'warehouse_id' => $wh2->warehouse_id,
                        'product_variant_id' => $variant->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                        'jumlah' => $stokWh2,
                        'sumber_tipe' => StockMovement::SUMBER_PRODUCTION_RESULT,
                        'sumber_id' => null,
                        'alasan' => 'Stok awal produksi',
                        'dibuat_oleh' => $firstGudangId,
                        'created_at' => now()->subDays(18 + $idx),
                    ];
                }
            }
            $products[] = $product;
        }

        // Pergerakan keluar (beberapa produk)
        $allVariants = ProductVariant::whereIn('product_id', Product::where('store_id', $store->store_id)->pluck('product_id'))->get();
        if ($allVariants->isNotEmpty()) {
            $v = $allVariants->first();
            $movements[] = [
                'warehouse_id' => $wh1->warehouse_id,
                'product_variant_id' => $v->product_variant_id,
                'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                'jumlah' => 5,
                'sumber_tipe' => StockMovement::SUMBER_ORDER_ITEM,
                'sumber_id' => null,
                'alasan' => 'Pemenuhan pesanan',
                'dibuat_oleh' => $firstGudangId,
                'created_at' => now()->subDays(5),
            ];
            WarehouseStock::where('warehouse_id', $wh1->warehouse_id)
                ->where('product_variant_id', $v->product_variant_id)
                ->decrement('jumlah_stok', 5);
        }

        // Pemindahan stok antar gudang
        $transfer = StockTransfer::create([
            'from_warehouse_id' => $wh1->warehouse_id,
            'to_warehouse_id' => $wh2->warehouse_id,
            'requested_by' => $firstGudangId,
            'approved_by' => null,
            'status' => StockTransfer::STATUS_RECEIVED,
            'diminta_pada' => now()->subDays(3),
            'diterima_pada' => now()->subDays(2),
        ]);
        if ($allVariants->isNotEmpty()) {
            StockTransferItem::create([
                'stock_transfer_id' => $transfer->stock_transfer_id,
                'product_variant_id' => $allVariants->first()->product_variant_id,
                'jumlah' => 10,
            ]);
            $movements[] = [
                'warehouse_id' => $wh1->warehouse_id,
                'product_variant_id' => $allVariants->first()->product_variant_id,
                'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
                'jumlah' => 10,
                'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                'sumber_id' => $transfer->stock_transfer_id,
                'alasan' => 'Pemindahan ke Gudang Cabang Jakarta',
                'dibuat_oleh' => $firstGudangId,
                'created_at' => now()->subDays(3),
            ];
            $movements[] = [
                'warehouse_id' => $wh2->warehouse_id,
                'product_variant_id' => $allVariants->first()->product_variant_id,
                'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
                'jumlah' => 10,
                'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                'sumber_id' => $transfer->stock_transfer_id,
                'alasan' => 'Penerimaan dari Gudang Utama Bandung',
                'dibuat_oleh' => $firstGudangId,
                'created_at' => now()->subDays(2),
            ];
        }

        if ($movements !== []) {
            StockMovement::insert($movements);
        }

        // ════════════════════════════════════════════════════════════════
        //  9. PROMO
        // ════════════════════════════════════════════════════════════════
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

        // ════════════════════════════════════════════════════════════════
        //  10. PESANAN + CHECKOUT + ORDER ITEMS
        // ════════════════════════════════════════════════════════════════
        $statuses = [Order::STATUS_SELESAI, Order::STATUS_DIKIRIM, Order::STATUS_DIPROSES, Order::STATUS_DIBAYAR, Order::STATUS_SELESAI, Order::STATUS_DIBATALKAN];
        $si = 0;
        $allOrderIds = [];

        $customerOrders = [
            ['email' => 'anindya@raliva.test', 'count' => 3],
            ['email' => 'bagus@raliva.test', 'count' => 2],
            ['email' => 'citra@raliva.test', 'count' => 4],
            ['email' => 'dimas@raliva.test', 'count' => 1],
            ['email' => 'eka@raliva.test', 'count' => 2],
            ['email' => 'customer@raliva.test', 'count' => 2],
        ];

        foreach ($customerOrders as $co) {
            $customer = $customers[$co['email']] ?? null;
            if (! $customer) {
                continue;
            }
            for ($o = 0; $o < $co['count']; $o++) {
                $variant = $allVariants->skip($si % $allVariants->count())->first() ?? $allVariants->first();
                if (! $variant) {
                    continue;
                }
                $qty = rand(1, 3);
                $harga = (float) $variant->harga;
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

                $allOrderIds[] = $order->order_id;
                $si++;
            }
        }

        // Pesanan untuk Pelanggan Request (dashboard Gudang)
        $requestStatuses = [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES];
        for ($r = 0; $r < 4; $r++) {
            $variant = $allVariants->skip($r % $allVariants->count())->first();
            if (! $variant) {
                continue;
            }
            $qty = 2;
            $harga = (float) $variant->harga;
            $subtotal = $harga * $qty;
            $customerEmail = $customerOrders[$r % count($customerOrders)]['email'];
            $customer = $customers[$customerEmail] ?? null;
            if (! $customer) {
                continue;
            }

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
                'nomor_order' => 'RQ-'.str_pad($r + 1, 4, '0'),
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $subtotal,
                'status' => $requestStatuses[$r % count($requestStatuses)],
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

            $variant2 = $allVariants->skip(($r + 2) % $allVariants->count())->first();
            if ($variant2 && $variant2->product_variant_id !== $variant->product_variant_id) {
                $qty2 = 1;
                $harga2 = (float) $variant2->harga;
                $subtotal2 = $harga2 * $qty2;

                $order->subtotal += $subtotal2;
                $order->grand_total += $subtotal2;
                $order->saveQuietly();
                $checkout->subtotal += $subtotal2;
                $checkout->grand_total += $subtotal2;
                $checkout->saveQuietly();

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_variant_id' => $variant2->product_variant_id,
                    'nama_produk_snapshot' => $variant2->product->nama_produk ?? 'Produk',
                    'harga_snapshot' => $harga2,
                    'quantity' => $qty2,
                    'subtotal' => $subtotal2,
                    'diskon' => 0,
                    'total' => $subtotal2,
                ]);
            }

            $allOrderIds[] = $order->order_id;
        }

        // ════════════════════════════════════════════════════════════════
        //  11. ULASAN
        // ════════════════════════════════════════════════════════════════
        $reviewProducts = collect($products)->take(4);
        $reviewCustomers = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Customer'))->take(4)->get();
        $reviewsData = [
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
                    'rating' => $reviewsData[$ri][1],
                    'ulasan' => $reviewsData[$ri][0],
                    'status' => Review::STATUS_AKTIF,
                ]
            );
        }

        // ════════════════════════════════════════════════════════════════
        //  12. WALLET + MUTASI + REKENING + PENCAIRAN
        // ════════════════════════════════════════════════════════════════
        $wallet = Wallet::create([
            'store_id' => $store->store_id,
            'saldo_tersedia' => 32500000,
            'saldo_tertahan' => 7100000,
        ]);

        $bca = Bank::where('kode_bank', 'bca')->first();
        $mandiri = Bank::where('kode_bank', 'mandiri')->first();

        $rekeningBca = StoreBankAccount::create([
            'store_id' => $store->store_id,
            'bank_id' => $bca?->bank_id,
            'nomor_rekening' => '812008821',
            'nama_pemilik' => $owner->nama_lengkap,
            'is_primary' => true,
            'status' => 'aktif',
        ]);

        StoreBankAccount::create([
            'store_id' => $store->store_id,
            'bank_id' => $mandiri?->bank_id,
            'nomor_rekening' => '130000077',
            'nama_pemilik' => $owner->nama_lengkap,
            'is_primary' => false,
            'status' => 'aktif',
        ]);

        $mutations = [
            ['jenis' => WalletTransaction::JENIS_PENJUALAN_MASUK, 'jumlah' => 3420000, 'ket' => 'Pesanan selesai — Anindya Putri (#RLV-2087)', 'wkt' => now()->subDays(10)],
            ['jenis' => WalletTransaction::JENIS_PENYESUAIAN, 'jumlah' => -1240000, 'ket' => 'Biaya layanan platform Agustus (INV-BIAYA-08)', 'wkt' => now()->subDays(9)],
            ['jenis' => WalletTransaction::JENIS_PENJUALAN_MASUK, 'jumlah' => 459000, 'ket' => 'Pesanan selesai — Bagus Pratama (#RLV-2090)', 'wkt' => now()->subDays(8)],
            ['jenis' => WalletTransaction::JENIS_REFUND_KELUAR, 'jumlah' => -450000, 'ket' => 'Komplain selesai — refund parsial (CMP-0034)', 'wkt' => now()->subDays(7)],
            ['jenis' => WalletTransaction::JENIS_PENJUALAN_MASUK, 'jumlah' => 1890000, 'ket' => 'Pesanan selesai — Citra Dewi (#RLV-2089)', 'wkt' => now()->subDays(5)],
            ['jenis' => WalletTransaction::JENIS_WITHDRAWAL, 'jumlah' => -25000000, 'ket' => 'Pencairan dana ke BCA ****8821 (WD-0092)', 'wkt' => now()->subDays(3)],
        ];

        $running = 32500000;
        foreach (array_reverse($mutations) as $m) {
            $running -= $m['jumlah'];
        }
        foreach ($mutations as $m) {
            $running += $m['jumlah'];
            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => $m['jenis'],
                'jumlah' => abs($m['jumlah']),
                'saldo_sebelum' => $running - $m['jumlah'],
                'saldo_sesudah' => $running,
                'keterangan' => $m['ket'],
                'created_at' => $m['wkt'],
                'updated_at' => $m['wkt'],
            ]);
        }

        // Pencairan dana
        $withdrawalRows = [
            ['jumlah' => 25000000, 'status' => Withdrawal::STATUS_PENDING, 'diajukan' => now()->subDays(2)],
            ['jumlah' => 20000000, 'status' => Withdrawal::STATUS_DIBAYAR, 'diajukan' => now()->subDays(15), 'dibayar' => now()->subDays(14)],
            ['jumlah' => 15500000, 'status' => Withdrawal::STATUS_DIBAYAR, 'diajukan' => now()->subDays(8), 'dibayar' => now()->subDays(7)],
            ['jumlah' => 30000000, 'status' => Withdrawal::STATUS_DITOLAK, 'diajukan' => now()->subDays(20), 'ditinjau' => now()->subDays(20), 'alasan' => 'Rekening tujuan tidak cocok dengan identitas Owner'],
        ];
        foreach ($withdrawalRows as $w) {
            Withdrawal::create(array_merge([
                'store_id' => $store->store_id,
                'wallet_id' => $wallet->wallet_id,
                'bank_account_id' => $rekeningBca->bank_account_id,
                'diajukan_pada' => $w['diajukan'],
            ], collect($w)->except(['jumlah', 'status', 'diajukan', 'dibayar', 'ditinjau', 'alasan'])->toArray(), [
                'jumlah' => $w['jumlah'],
                'status' => $w['status'],
                'dibayar_pada' => $w['dibayar'] ?? null,
                'ditinjau_pada' => $w['ditinjau'] ?? null,
                'alasan_penolakan' => $w['alasan'] ?? null,
            ]));
        }

        // ════════════════════════════════════════════════════════════════
        //  13. PENGELUARAN TOKO
        // ════════════════════════════════════════════════════════════════
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

        // ════════════════════════════════════════════════════════════════
        //  14. DOKUMEN TOKO
        // ════════════════════════════════════════════════════════════════
        foreach (['ktp', 'npwp', 'foto_depan', 'siu'] as $jenis) {
            StoreDocument::updateOrCreate(
                ['store_id' => $store->store_id, 'jenis' => $jenis],
                ['path' => 'store-documents/'.$store->store_id.'/'.$jenis.'.pdf', 'status' => 'terverifikasi', 'catatan' => null]
            );
        }

        // ════════════════════════════════════════════════════════════════
        //  15. KOMPLAIN + PESAN KOMPLAIN
        // ════════════════════════════════════════════════════════════════
        $custForComplaint = $customers['customer@raliva.test'] ?? $customers[array_key_first($customers)];
        $orderForComplaint = Order::where('store_id', $store->store_id)->first();

        $complaintsSeed = [
            ['Barang rusak', 'Kemeja item diterima sobek di bagian lengan.', 'baru'],
            ['Salah kirim warna', 'Pesanan warna navy tapi datang black.', 'diproses'],
            ['Pengiriman terlambat', 'Estimasi 3 hari tapi 7 hari baru sampai.', 'selesai'],
        ];
        foreach ($complaintsSeed as $ci => $c) {
            $complaint = Complaint::create([
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

            // Pesan komplain untuk yang diproses
            if ($c[2] === 'diproses' && $complaint) {
                ComplaintMessage::create([
                    'complaint_id' => $complaint->complaint_id,
                    'sender_id' => $custForComplaint?->user_id,
                    'pesan' => 'Halo, saya ingin komplain karena pesanan datang dalam kondisi salah.',
                ]);
                $adminUser = $staffModels['admin@raliva.test'] ?? null;
                if ($adminUser) {
                    ComplaintMessage::create([
                        'complaint_id' => $complaint->complaint_id,
                        'sender_id' => $adminUser->user_id,
                        'pesan' => 'Terima kasih informasinya, kami akan proses penggantian barangnya.',
                    ]);
                }
            }
        }

        // ════════════════════════════════════════════════════════════════
        //  16. PERMINTAAN PRODUKSI
        // ════════════════════════════════════════════════════════════════
        $variantDemo = ProductVariant::whereIn('product_id', Product::where('store_id', $store->store_id)->pluck('product_id'))->first();
        $poSeed = [
            ['PO-001', 'tinggi', 'diproses'],
            ['PO-002', 'sedang', 'selesai'],
        ];
        foreach ($poSeed as $p) {
            $po = ProductionOrder::create([
                'store_id' => $store->store_id,
                'requested_by' => $owner->user_id,
                'assigned_to' => null,
                'target_warehouse_id' => $wh1->warehouse_id,
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

        // ════════════════════════════════════════════════════════════════
        //  17. SUPPLIER
        // ════════════════════════════════════════════════════════════════
        $suppliers = [
            ['nama_supplier' => 'CV Tekstil Bandung', 'kota' => 'Bandung', 'kontak' => 'Budi Santoso', 'email' => 'budi@tekstilbandung.co.id', 'jenis' => 'kain', 'status' => 'aktif', 'catatan' => 'Minimum order 50 meter.'],
            ['nama_supplier' => 'Aksesoris Mega', 'kota' => 'Jakarta', 'kontak' => 'Sari Wijaya', 'email' => 'sari@aksesorismega.com', 'jenis' => 'aksesoris', 'status' => 'verifikasi', 'catatan' => 'Pengiriman 3-5 hari.'],
            ['nama_supplier' => 'Kemasan Prima', 'kota' => 'Surabaya', 'kontak' => 'Joko Pratomo', 'email' => 'joko@kemasanprima.id', 'jenis' => 'kemasan', 'status' => 'aktif', 'catatan' => 'Box kustom tersedia.'],
        ];
        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['email' => $s['email']], $s);
        }

        // ════════════════════════════════════════════════════════════════
        //  18. PEMBAYARAN + BUKTI + REFUND
        // ════════════════════════════════════════════════════════════════
        $paymentMethod = PaymentMethod::first();

        // Refund cases
        $refundOrders = Order::with('checkout')->where('store_id', $store->store_id)->limit(5)->get();
        $refundCases = [
            ['tipe' => Refund::TIPE_PARTIAL, 'alasan' => 'Barang tidak sesuai deskripsi — warna berbeda', 'status' => Refund::STATUS_REQUESTED],
            ['tipe' => Refund::TIPE_FULL, 'alasan' => 'Paket hilang dalam pengiriman', 'status' => Refund::STATUS_DISETUJUI],
            ['tipe' => Refund::TIPE_FULL, 'alasan' => 'Ukuran tidak pas', 'status' => Refund::STATUS_SELESAI],
        ];
        foreach ($refundOrders as $ri => $order) {
            if (! $order->checkout || $ri >= count($refundCases)) {
                continue;
            }
            $rc = $refundCases[$ri];
            $payment = Payment::firstOrCreate(
                ['checkout_id' => $order->checkout->checkout_id],
                [
                    'payment_method_id' => $paymentMethod?->payment_method_id ?? 1,
                    'jumlah' => $order->grand_total,
                    'status' => 'lunas',
                    'batas_waktu' => now()->addDay(),
                    'dibayar_pada' => now()->subDays(2),
                ]
            );
            Refund::create([
                'order_id' => $order->order_id,
                'payment_id' => $payment->payment_id,
                'requested_by' => $order->checkout->user_id,
                'tipe_refund' => $rc['tipe'],
                'alasan' => $rc['alasan'],
                'jumlah' => $order->grand_total,
                'status' => $rc['status'],
                'diajukan_pada' => now()->subDay(),
                'selesai_pada' => $rc['status'] === Refund::STATUS_SELESAI ? now() : null,
            ]);
        }

        // Pembayaran menunggu verifikasi
        $pendingOrders = Order::with('checkout')->where('store_id', $store->store_id)
            ->whereDoesntHave('checkout.payment')->limit(3)->get();
        foreach ($pendingOrders as $po) {
            if (! $po->checkout) {
                continue;
            }
            $pay = Payment::create([
                'checkout_id' => $po->checkout->checkout_id,
                'payment_method_id' => $paymentMethod?->payment_method_id ?? 1,
                'jumlah' => $po->grand_total,
                'status' => Payment::STATUS_MENUNGGU_VERIFIKASI,
                'batas_waktu' => now()->addDay(),
            ]);
            PaymentProof::create([
                'payment_id' => $pay->payment_id,
                'file_bukti' => 'proofs/sample-'.$pay->payment_id.'.jpg',
                'uploaded_at' => now(),
            ]);
        }

        // ════════════════════════════════════════════════════════════════
        //  19. NOTIFIKASI untuk semua staff
        // ════════════════════════════════════════════════════════════════
        $notifStaff = array_filter($staffModels, fn ($u) => in_array($u->email, ['admin@raliva.test', 'gudang@raliva.test', 'gudang2@raliva.test', 'gudang3@raliva.test']));
        foreach ($notifStaff as $user) {
            Notification::insert([
                [
                    'user_id' => $user->user_id,
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Stok Menipis',
                    'pesan' => 'Stok <strong>Silk Scarf</strong> tinggal 5 unit di Gudang Utama Bandung.',
                    'dibaca_pada' => null,
                    'created_at' => now()->subMinutes(10),
                    'updated_at' => now()->subMinutes(10),
                ],
                [
                    'user_id' => $user->user_id,
                    'tipe' => Notification::TIPE_PEMBAYARAN,
                    'judul' => 'Pembayaran Menunggu',
                    'pesan' => 'Bukti pembayaran pesanan <strong>#RLV-2081</strong> menunggu verifikasi.',
                    'dibaca_pada' => null,
                    'created_at' => now()->subMinutes(30),
                    'updated_at' => now()->subMinutes(30),
                ],
            ]);
        }

        // ════════════════════════════════════════════════════════════════
        //  20. LOG AKTIVITAS
        // ════════════════════════════════════════════════════════════════
        if (ActivityLog::count() === 0) {
            $adminLog = $staffModels['admin@raliva.test'] ?? null;
            $logs = [
                ['aksi' => 'verifikasi_pembayaran', 'deskripsi' => 'Kamu memverifikasi pembayaran <strong>#RLV-2076</strong> sebesar <strong>Rp 1.150.000</strong>.', 'target' => 'Transaksi'],
                ['aksi' => 'resi', 'deskripsi' => 'Kamu memasukkan resi <strong>JNE2608210041</strong> untuk pesanan <strong>#RLV-2075</strong>.', 'target' => 'Pengiriman'],
                ['aksi' => 'komplain', 'deskripsi' => 'Kamu membalas komplain <strong>CMP-001</strong> dari <strong>Andi Pratama</strong>.', 'target' => 'Komplain'],
                ['aksi' => 'refund', 'deskripsi' => 'Kamu menyetujui pengembalian dana untuk pesanan <strong>#RLV-2069</strong>.', 'target' => 'Refund'],
                ['aksi' => 'produk', 'deskripsi' => 'Kamu memperbarui stok <strong>Oversized Linen Shirt</strong>.', 'target' => 'Produk'],
            ];
            foreach ($logs as $i => $l) {
                ActivityLog::create([
                    'user_id' => $adminLog?->user_id,
                    'aksi' => $l['aksi'],
                    'target_tipe' => $l['target'],
                    'deskripsi' => $l['deskripsi'],
                    'created_at' => now()->subHours($i * 5),
                ]);
            }
        }

        $this->command?->info(
            'RalivaDemoSeeder selesai: '
            .'Toko #'.$store->store_id
            .', '.Product::where('store_id', $store->store_id)->count().' produk'
            .', '.Order::where('store_id', $store->store_id)->count().' pesanan'
            .', '.Warehouse::where('store_id', $store->store_id)->count().' gudang'
            .', '.StoreStaff::where('store_id', $store->store_id)->count().' staff'
            .', '.Wallet::where('store_id', $store->store_id)->count().' wallet'
        );
    }

    private function defaultOperationalHours(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $hours = [];
        foreach ($days as $day) {
            $isWeekend = $day === 'Minggu';
            $hours[$day] = [
                'buka' => ! $isWeekend,
                'mulai' => $isWeekend ? null : '09:00',
                'selesai' => $isWeekend ? null : '21:00',
            ];
        }

        return $hours;
    }
}
