<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Store;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStaff;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GudangDemoSeeder extends Seeder
{
    /**
     * Seed data persediaan agar halaman role Gudang menampilkan data nyata.
     * Dijalankan: php artisan db:seed --class=GudangDemoSeeder
     */
    public function run(): void
    {
        $store = Store::firstOrCreate(['store_id' => 1], [
            'owner_id' => 4,
            'nama_toko' => 'Raliva Atelier Jakarta',
            'status' => 'aktif',
        ]);

        // Dua gudang dalam satu toko untuk demo fitur "Ganti Gudang".
        $wh1 = Warehouse::firstOrCreate(
            ['warehouse_id' => 1],
            ['store_id' => $store->store_id, 'nama_gudang' => 'Gudang Utama Bandung', 'alamat' => 'Jl. Soekarno Hatta No. 12, Bandung', 'nomor_telepon' => '081234000001', 'status' => 'aktif']
        );
        $wh2 = Warehouse::firstOrCreate(
            ['warehouse_id' => 2],
            ['store_id' => $store->store_id, 'nama_gudang' => 'Gudang Cabang Jakarta', 'alamat' => 'Jl. Sudirman No. 45, Jakarta', 'nomor_telepon' => '081234000002', 'status' => 'aktif']
        );

        // Tugaskan seluruh user Gudang ke kedua gudang (status aktif).
        $gudangUsers = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))->get();
        if ($gudangUsers->isEmpty()) {
            $this->command?->warn('Tidak ada user dengan role Gudang. Lewati penugasan staf.');
        }
        foreach ($gudangUsers as $user) {
            foreach ([$wh1->warehouse_id, $wh2->warehouse_id] as $whId) {
                WarehouseStaff::firstOrCreate(
                    ['warehouse_id' => $whId, 'user_id' => $user->user_id],
                    ['tanggal_penugasan' => now()->subMonths(3), 'status' => 'aktif']
                );
            }
        }

        // Bersihkan data persediaan lama agar seeding deterministik.
        WarehouseStock::query()->delete();
        StockMovement::query()->delete();
        StockTransferItem::query()->delete();
        StockTransfer::query()->delete();
        ProductVariant::query()->delete();
        Product::query()->delete();

        $categories = Category::whereIn('nama_kategori', [
            'Kemeja', 'Kaos', 'Celana', 'Jaket & Hoodie', 'Dress', 'Aksesori',
        ])->get()->keyBy('nama_kategori');

        $cat = fn (string $name) => ($categories[$name] ?? $categories->first())->category_id;

        $seed = [
            ['Oversized Linen Shirt', 'Kemeja', 'KEM', [['S', 12], ['M', 20], ['L', 8], ['XL', 6]], 180000, 289000, 15],
            ['Straight Fit Pants', 'Celana', 'CEL', [['28', 14], ['30', 12], ['32', 9], ['34', 7]], 210000, 329000, 20],
            ['Relaxed Blazer', 'Jaket & Hoodie', 'BLZ', [['M', 18], ['L', 16]], 320000, 549000, 12],
            ['Knit Cardigan Rajut', 'Jaket & Hoodie', 'RDG', [['S', 10], ['M', 14], ['L', 10]], 185000, 299000, 10],
            ['Midi Dress Linen', 'Dress', 'DRS', [['S', 20], ['M', 22], ['L', 16]], 220000, 389000, 15],
            ['Basic T-Shirt Cotton', 'Kaos', 'KSL', [['S', 40], ['M', 52], ['L', 30], ['XL', 20]], 55000, 99000, 40],
            ['Denim Jacket Classic', 'Jaket & Hoodie', 'JKT', [['M', 10], ['L', 8], ['XL', 6]], 280000, 459000, 10],
            ['Pleated Skirt', 'Dress', 'RKT', [['S', 12], ['M', 10], ['L', 5]], 165000, 275000, 10],
            ['Wide Leg Trousers', 'Celana', 'CLT', [['28', 4], ['30', 6], ['32', 3]], 175000, 295000, 15],
            ['Hoodie Fleece Premium', 'Jaket & Hoodie', 'HDD', [['M', 28], ['L', 22], ['XL', 13]], 210000, 359000, 20],
            ['Silk Scarf', 'Aksesori', 'SYL', [['One Size', 5]], 95000, 185000, 15],
            ['Leather Belt', 'Aksesori', 'IKT', [['85-105 cm', 30], ['90-110 cm', 28]], 110000, 199000, 25],
        ];

        $movements = [];
        $transfers = [];

        foreach ($seed as $idx => $row) {
            [$nama, $kategori, $prefix, $variants, $hpp, $harga, $min] = $row;

            $product = Product::create([
                'store_id' => $store->store_id,
                'category_id' => $cat($kategori),
                'nama_produk' => $nama,
                'deskripsi' => "$nama produksi lokal Raliva — bahan premium, jahitan rapi.",
                'harga_dasar' => $harga,
                'tipe_produk' => 'regular',
                'status' => 'aktif',
            ]);

            $variantIds = [];
            foreach ($variants as $vi => [$warna, $stokAwal]) {
                $variant = ProductVariant::create([
                    'product_id' => $product->product_id,
                    'sku' => $prefix . '-' . str_pad($idx + 1, 3, '0') . '-' . ($vi + 1),
                    'warna' => $warna,
                    'ukuran' => null,
                    'harga' => $harga,
                    'status' => 'aktif',
                ]);
                $variantIds[] = $variant;

                // Sebar stok ke gudang 1 dan sebagian ke gudang 2.
                $stokWh1 = (int) round($stokAwal * 0.7);
                $stokWh2 = $stokAwal - $stokWh1;

                WarehouseStock::create([
                    'warehouse_id' => $wh1->warehouse_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'jumlah_stok' => max($stokWh1, 0),
                    'jumlah_direservasi' => 0,
                    'stok_minimum' => $min,
                ]);
                if ($stokWh2 > 0) {
                    WarehouseStock::create([
                        'warehouse_id' => $wh2->warehouse_id,
                        'product_variant_id' => $variant->product_variant_id,
                        'jumlah_stok' => $stokWh2,
                        'jumlah_direservasi' => 0,
                        'stok_minimum' => $min,
                    ]);
                }

                // Catat pergerakan masuk awal (sumber produksi).
                $movements[] = [
                    'warehouse_id' => $wh1->warehouse_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'tipe_pergerakan' => StockMovement::TIPE_MASUK,
                    'jumlah' => $stokWh1,
                    'sumber_tipe' => StockMovement::SUMBER_PRODUCTION_RESULT,
                    'sumber_id' => null,
                    'alasan' => 'Stok awal produksi',
                    'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
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
                        'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
                        'created_at' => now()->subDays(18 + $idx),
                    ];
                }
            }

            // Beberapa produk dapat pergerakan keluar (pesanan) & pemeriksaan.
            if ($idx % 3 === 0) {
                $v = $variantIds[0];
                $movements[] = [
                    'warehouse_id' => $wh1->warehouse_id,
                    'product_variant_id' => $v->product_variant_id,
                    'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                    'jumlah' => 5,
                    'sumber_tipe' => StockMovement::SUMBER_ORDER_ITEM,
                    'sumber_id' => null,
                    'alasan' => 'Pemenuhan pesanan',
                    'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
                    'created_at' => now()->subDays(5 + $idx),
                ];
                // kurangi stok agar konsisten
                WarehouseStock::where('warehouse_id', $wh1->warehouse_id)
                    ->where('product_variant_id', $v->product_variant_id)
                    ->decrement('jumlah_stok', 5);
            }
            if ($idx % 4 === 1) {
                $v = $variantIds[0];
                $movements[] = [
                    'warehouse_id' => $wh1->warehouse_id,
                    'product_variant_id' => $v->product_variant_id,
                    'tipe_pergerakan' => StockMovement::TIPE_PENYESUAIAN,
                    'jumlah' => 2,
                    'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                    'sumber_id' => null,
                    'alasan' => 'Pemeriksaan fisik: selisih +2',
                    'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
                    'created_at' => now()->subDays(2 + $idx),
                ];
                WarehouseStock::where('warehouse_id', $wh1->warehouse_id)
                    ->where('product_variant_id', $v->product_variant_id)
                    ->increment('jumlah_stok', 2);
            }
        }

        // Pemindahan stok antar gudang (requested -> received).
        $transfer = StockTransfer::create([
            'from_warehouse_id' => $wh1->warehouse_id,
            'to_warehouse_id' => $wh2->warehouse_id,
            'requested_by' => $gudangUsers->first()->user_id ?? 1,
            'approved_by' => null,
            'status' => StockTransfer::STATUS_RECEIVED,
            'diminta_pada' => now()->subDays(3),
            'diterima_pada' => now()->subDays(2),
        ]);
        $firstVariant = ProductVariant::first();
        StockTransferItem::create([
            'stock_transfer_id' => $transfer->stock_transfer_id,
            'product_variant_id' => $firstVariant->product_variant_id,
            'jumlah' => 10,
        ]);
        $movements[] = [
            'warehouse_id' => $wh1->warehouse_id,
            'product_variant_id' => $firstVariant->product_variant_id,
            'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
            'jumlah' => 10,
            'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
            'sumber_id' => $transfer->stock_transfer_id,
            'alasan' => 'Pemindahan ke Gudang Cabang Jakarta',
            'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
            'created_at' => now()->subDays(3),
        ];
        $movements[] = [
            'warehouse_id' => $wh2->warehouse_id,
            'product_variant_id' => $firstVariant->product_variant_id,
            'tipe_pergerakan' => StockMovement::TIPE_MUTASI_MASUK,
            'jumlah' => 10,
            'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
            'sumber_id' => $transfer->stock_transfer_id,
            'alasan' => 'Penerimaan dari Gudang Utama Bandung',
            'dibuat_oleh' => $gudangUsers->first()->user_id ?? 1,
            'created_at' => now()->subDays(2),
        ];

        // Insert semua movement sekaligus.
        StockMovement::insert($movements);

        // Beberapa pesanan pelanggan yang menunggu pemenuhan gudang
        // (status dibayar/diproses) — mengisi card "Pelanggan Request" di dashboard
        // dan halaman Pelanggan Request, konsisten dengan store gudang ini.
        $customer = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Customer'))->first()
            ?? \App\Models\User::firstOrCreate(
                ['email' => 'pelanggan.demo@raliva.test'],
                ['nama_lengkap' => 'Pelanggan Demo', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role_id' => \App\Models\Role::where('nama_role', 'Customer')->value('role_id'), 'nomor_telepon' => '081234000099', 'status' => 'aktif']
            );

        $requestVariantIds = \App\Models\WarehouseStock::where('warehouse_id', $wh1->warehouse_id)
            ->limit(4)
            ->pluck('product_variant_id')
            ->all();
        $requestVariants = \App\Models\ProductVariant::whereIn('product_variant_id', $requestVariantIds)->get();

        $requestStatuses = [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES, \App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES];
        foreach ($requestStatuses as $idx => $status) {
            $variant = $requestVariants[$idx % $requestVariants->count()] ?? \App\Models\ProductVariant::first();
            if (! $variant) {
                continue;
            }
            $qty = 2;
            $harga = (float) ($variant->harga ?? $variant->product->harga_dasar ?? 0);
            $subtotal = $harga * $qty;

            $checkout = \App\Models\Checkout::create([
                'user_id' => $customer->user_id,
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $subtotal,
                'status' => 'paid',
            ]);

            $order = \App\Models\Order::create([
                'checkout_id' => $checkout->checkout_id,
                'store_id' => $store->store_id,
                'nomor_order' => 'RQ-' . str_pad($idx + 1, 4, '0'),
                'subtotal' => $subtotal,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $subtotal,
                'status' => $status,
            ]);

            \App\Models\OrderItem::create([
                'order_id' => $order->order_id,
                'product_variant_id' => $variant->product_variant_id,
                'nama_produk_snapshot' => $variant->product->nama_produk ?? 'Produk',
                'harga_snapshot' => $harga,
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'diskon' => 0,
                'total' => $subtotal,
            ]);
        }

        // Notifikasi untuk user gudang.
        foreach ($gudangUsers as $user) {
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
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Barang Masuk',
                    'pesan' => 'Barang masuk <strong>BM-0012</strong> menunggu pemeriksaan.',
                    'dibaca_pada' => null,
                    'created_at' => now()->subMinutes(30),
                    'updated_at' => now()->subMinutes(30),
                ],
                [
                    'user_id' => $user->user_id,
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Pemindahan Stok',
                    'pesan' => 'Pemindahan stok <strong>PM-0004</strong> telah diterima.',
                    'dibaca_pada' => now()->subHours(2),
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHours(2),
                ],
            ]);
        }

        $this->command?->info('GudangDemoSeeder selesai: ' . Warehouse::count() . ' gudang, ' . Product::count() . ' produk, ' . WarehouseStock::count() . ' stok, ' . StockMovement::count() . ' pergerakan.');
    }
}
