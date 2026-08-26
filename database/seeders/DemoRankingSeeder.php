<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoRankingSeeder extends Seeder
{
    private int $orderCounter = 0;

    public function run(): void
    {
        mt_srand(20260826);

        $ownerRoleId = Role::where('nama_role', Role::OWNER)->value('role_id');
        $customerRoleId = Role::where('nama_role', Role::CUSTOMER)->value('role_id');

        $owners = $this->seedUsers([
            ['nama_lengkap' => 'Elara Vance', 'email' => 'elara@raliva.test'],
            ['nama_lengkap' => 'Julian Thorne', 'email' => 'julian@raliva.test'],
            ['nama_lengkap' => 'Maya Rossi', 'email' => 'maya@raliva.test'],
            ['nama_lengkap' => 'Sofia Laurent', 'email' => 'sofia@raliva.test'],
            ['nama_lengkap' => 'Nadia Prameswari', 'email' => 'nadia@raliva.test'],
        ], $ownerRoleId);

        $customers = $this->seedUsers([
            ['nama_lengkap' => 'Sarah Jenkins', 'email' => 'sarah.jenkins@mail.test'],
            ['nama_lengkap' => 'Dewi Lestari', 'email' => 'dewi.lestari@mail.test'],
            ['nama_lengkap' => 'Andi Pratama', 'email' => 'andi.pratama@mail.test'],
            ['nama_lengkap' => 'Rizky Hidayat', 'email' => 'rizky.hidayat@mail.test'],
            ['nama_lengkap' => 'Chen Wei', 'email' => 'chen.wei@mail.test'],
            ['nama_lengkap' => 'Putri Maharani', 'email' => 'putri.maharani@mail.test'],
        ], $customerRoleId);

        $stores = $this->seedStores($owners);
        $productsByStore = $this->seedProducts($stores);
        $this->seedOrders($stores, $productsByStore, $customers);
    }

    private function seedUsers(array $users, ?int $roleId): array
    {
        return collect($users)->mapWithKeys(function ($data) use ($roleId) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nama_lengkap' => $data['nama_lengkap'],
                    'password' => Hash::make('password'),
                    'role_id' => $roleId,
                    'nomor_telepon' => '08'.mt_rand(1111111111, 9999999999),
                    'status' => User::STATUS_AKTIF,
                ]
            );

            return [$data['nama_lengkap'] => $user];
        })->all();
    }

    private function seedStores(array $owners): array
    {
        $stores = [
            ['nama_toko' => 'LUNARA Fashion', 'owner' => 'Elara Vance', 'alamat' => 'Jl. Riau No. 21, Bandung', 'deskripsi' => 'Fashion feminin modern dengan bahan lokal premium.', 'jumlah_pesanan' => 14],
            ['nama_toko' => 'NOIRÉ Studio', 'owner' => 'Julian Thorne', 'alamat' => 'Jl. Kemang Selatan No. 8, Jakarta', 'deskripsi' => 'Koleksi esensial minimalis sehari-hari dengan bahan berkelanjutan.', 'jumlah_pesanan' => 11],
            ['nama_toko' => 'KAYANA Apparel', 'owner' => 'Maya Rossi', 'alamat' => 'Jl. Darmo No. 45, Surabaya', 'deskripsi' => 'Streetwear eklektik produksi terbatas dengan sablon manual.', 'jumlah_pesanan' => 9],
            ['nama_toko' => 'Velvet Closet', 'owner' => 'Sofia Laurent', 'alamat' => 'Jl. Tirtodipuran No. 12, Yogyakarta', 'deskripsi' => 'Tas dan aksesori artisan dengan sentuhan klasik.', 'jumlah_pesanan' => 7],
            ['nama_toko' => 'MAÉVA House', 'owner' => 'Nadia Prameswari', 'alamat' => 'Jl. Pandanaran No. 30, Semarang', 'deskripsi' => 'Muslim fashion nyaman untuk keseharian yang aktif.', 'jumlah_pesanan' => 5],
        ];

        return collect($stores)->mapWithKeys(function ($data) use ($owners) {
            $store = Store::updateOrCreate(
                ['nama_toko' => $data['nama_toko']],
                [
                    'owner_id' => $owners[$data['owner']]->user_id,
                    'deskripsi' => $data['deskripsi'],
                    'alamat' => $data['alamat'],
                    'nomor_telepon' => '08'.mt_rand(1111111111, 9999999999),
                    'status' => Store::STATUS_AKTIF,
                ]
            );

            return [$data['nama_toko'] => [
                'store' => $store,
                'jumlah_pesanan' => $data['jumlah_pesanan'],
            ]];
        })->all();
    }

    private function seedProducts(array $stores): array
    {
        $blueprint = [
            'LUNARA Fashion' => [
                ['nama_produk' => 'Midi Dress Linen', 'kategori' => 'Dress', 'harga_dasar' => 289000],
                ['nama_produk' => 'Silk Blouse Premium', 'kategori' => 'Blouse', 'harga_dasar' => 199000],
                ['nama_produk' => 'Relaxed Blazer', 'kategori' => 'Atasan', 'harga_dasar' => 579000],
            ],
            'NOIRÉ Studio' => [
                ['nama_produk' => 'Oversized Linen Shirt', 'kategori' => 'Kemeja', 'harga_dasar' => 289000],
                ['nama_produk' => 'Straight Fit Pants', 'kategori' => 'Celana', 'harga_dasar' => 329000],
            ],
            'KAYANA Apparel' => [
                ['nama_produk' => 'Graphic Tee Premium', 'kategori' => 'Kaos', 'harga_dasar' => 149000],
                ['nama_produk' => 'Denim Jacket Oversize', 'kategori' => 'Jaket & Hoodie', 'harga_dasar' => 459000],
            ],
            'Velvet Closet' => [
                ['nama_produk' => 'Tote Bag Canvas', 'kategori' => 'Tote Bag', 'harga_dasar' => 129000],
                ['nama_produk' => 'Mini Backpack Kulit', 'kategori' => 'Tas Punggung', 'harga_dasar' => 389000],
            ],
            'MAÉVA House' => [
                ['nama_produk' => 'Hijab Voal Premium', 'kategori' => 'Hijab', 'harga_dasar' => 89000],
                ['nama_produk' => 'Gamis Airflow', 'kategori' => 'Gamis', 'harga_dasar' => 359000],
            ],
        ];

        $result = [];

        foreach ($blueprint as $storeName => $items) {
            $storeId = $stores[$storeName]['store']->store_id;

            $result[$storeName] = collect($items)->map(function ($item) use ($storeId, $storeName) {
                $category = Category::where('nama_kategori', $item['kategori'])->firstOrFail();

                $product = Product::updateOrCreate(
                    ['nama_produk' => $item['nama_produk'], 'store_id' => $storeId],
                    [
                        'category_id' => $category->category_id,
                        'deskripsi' => $item['nama_produk'].' berkualitas premium dari '.$storeName.'.',
                        'harga_dasar' => $item['harga_dasar'],
                        'tipe_produk' => Product::TIPE_REGULAR,
                        'status' => Product::STATUS_AKTIF,
                    ]
                );

                $variant = ProductVariant::updateOrCreate(
                    ['sku' => 'SKU-'.strtoupper(substr(md5($storeName.$item['nama_produk']), 0, 8)).'-1'],
                    [
                        'product_id' => $product->product_id,
                        'warna' => 'Hitam',
                        'ukuran' => 'M',
                        'harga' => $item['harga_dasar'],
                        'status' => 'aktif',
                    ]
                );

                return [
                    'product' => $product,
                    'variant' => $variant,
                    'harga' => $item['harga_dasar'],
                ];
            })->all();
        }

        return $result;
    }

    private function seedOrders(array $stores, array $productsByStore, array $customers): void
    {
        $statusPool = [
            Order::STATUS_SELESAI,
            Order::STATUS_SELESAI,
            Order::STATUS_DIKIRIM,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIBAYAR,
        ];

        $loyaltyWeight = [
            'Sarah Jenkins' => 4,
            'Dewi Lestari' => 3,
            'Andi Pratama' => 2,
            'Rizky Hidayat' => 2,
            'Chen Wei' => 1,
            'Putri Maharani' => 2,
        ];

        foreach ($stores as $storeName => $payload) {
            $store = $payload['store'];
            $products = $productsByStore[$storeName];

            for ($i = 0; $i < $payload['jumlah_pesanan']; $i++) {
                $customerName = $this->pickWeighted(array_keys($customers), $loyaltyWeight);
                $daysAgo = mt_rand(1, 75);

                if ($daysAgo <= 3 && mt_rand(1, 100) <= 25) {
                    $status = mt_rand(1, 2) === 1 ? Order::STATUS_PENDING_PAYMENT : Order::STATUS_DIBATALKAN;
                } else {
                    $status = $statusPool[array_rand($statusPool)];
                }

                $timestamp = now()->subDays($daysAgo)->subHours(mt_rand(1, 10));

                $lines = [];
                for ($j = 0; $j < mt_rand(1, 2); $j++) {
                    $line = $products[array_rand($products)];
                    $quantity = mt_rand(1, 2);

                    $lines[] = [
                        'line' => $line,
                        'quantity' => $quantity,
                        'total' => $line['harga'] * $quantity,
                    ];
                }

                $subtotal = array_sum(array_column($lines, 'total'));
                $totalPajak = round($subtotal * 0.11);
                $biayaLayanan = 1000;
                $totalOngkir = mt_rand(10, 25) * 1000;
                $grandTotal = $subtotal + $totalPajak + $biayaLayanan + $totalOngkir;

                $checkout = new Checkout([
                    'user_id' => $customers[$customerName]->user_id,
                    'subtotal' => $subtotal,
                    'total_diskon' => 0,
                    'total_pajak' => $totalPajak,
                    'biaya_layanan' => $biayaLayanan,
                    'total_ongkir' => $totalOngkir,
                    'grand_total' => $grandTotal,
                    'status' => $status === Order::STATUS_PENDING_PAYMENT ? Checkout::STATUS_PENDING : Checkout::STATUS_DIBAYAR,
                ]);
                $checkout->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();

                $order = new Order([
                    'checkout_id' => $checkout->checkout_id,
                    'store_id' => $store->store_id,
                    'nomor_order' => $this->nextOrderNumber(),
                    'subtotal' => $subtotal,
                    'total_diskon' => 0,
                    'total_pajak' => $totalPajak,
                    'biaya_layanan' => $biayaLayanan,
                    'total_ongkir' => $totalOngkir,
                    'grand_total' => $grandTotal,
                    'status' => $status,
                ]);
                $order->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();

                foreach ($lines as $lineData) {
                    $orderItem = new OrderItem([
                        'order_id' => $order->order_id,
                        'product_variant_id' => $lineData['line']['variant']->product_variant_id,
                        'nama_produk_snapshot' => $lineData['line']['product']->nama_produk,
                        'harga_snapshot' => $lineData['line']['harga'],
                        'quantity' => $lineData['quantity'],
                        'subtotal' => $lineData['total'],
                        'diskon' => 0,
                        'total' => $lineData['total'],
                    ]);
                    $orderItem->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->save();

                    if (in_array($status, [Order::STATUS_SELESAI, Order::STATUS_DIKIRIM], true) && mt_rand(1, 100) <= 65) {
                        $ratingPool = $storeName === 'KAYANA Apparel' ? [3, 3, 4] : [4, 5, 5];

                        $reviewAt = $timestamp->copy()->addDays(2);
                        $review = new Review([
                            'user_id' => $customers[$customerName]->user_id,
                            'order_item_id' => $orderItem->order_item_id,
                            'product_id' => $lineData['line']['product']->product_id,
                            'store_id' => $store->store_id,
                            'rating' => $ratingPool[array_rand($ratingPool)],
                            'ulasan' => 'Produk sesuai deskripsi, pengiriman rapi.',
                            'status' => Review::STATUS_AKTIF,
                        ]);
                        $review->forceFill(['created_at' => $reviewAt, 'updated_at' => $reviewAt])->save();
                    }
                }
            }
        }
    }

    private function pickWeighted(array $names, array $weight): string
    {
        $pool = [];

        foreach ($names as $name) {
            $pool = array_merge($pool, array_fill(0, $weight[$name] ?? 1, $name));
        }

        return $pool[array_rand($pool)];
    }

    private function nextOrderNumber(): string
    {
        $this->orderCounter++;

        return 'ORD-'.now()->format('Ymd').'-'.str_pad((string) $this->orderCounter, 4, '0', STR_PAD_LEFT);
    }
}
