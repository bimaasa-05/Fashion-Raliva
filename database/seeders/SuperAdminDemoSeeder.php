<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRoleId = Role::where('nama_role', Role::OWNER)->value('role_id');

        $owners = [
            'elara' => User::where('email', 'elara@raliva.test')->first()
                ?? User::create(['nama_lengkap' => 'Elara Vance', 'email' => 'elara@raliva.test', 'password' => bcrypt('password'), 'role_id' => $ownerRoleId, 'status' => User::STATUS_AKTIF]),
            'julian' => User::where('email', 'julian@raliva.test')->first()
                ?? User::create(['nama_lengkap' => 'Julian Thorne', 'email' => 'julian@raliva.test', 'password' => bcrypt('password'), 'role_id' => $ownerRoleId, 'status' => User::STATUS_AKTIF]),
            'maya' => User::where('email', 'maya@raliva.test')->first()
                ?? User::create(['nama_lengkap' => 'Maya Rossi', 'email' => 'maya@raliva.test', 'password' => bcrypt('password'), 'role_id' => $ownerRoleId, 'status' => User::STATUS_AKTIF]),
            'sofia' => User::where('email', 'sofia@raliva.test')->first()
                ?? User::create(['nama_lengkap' => 'Sofia Laurent', 'email' => 'sofia@raliva.test', 'password' => bcrypt('password'), 'role_id' => $ownerRoleId, 'status' => User::STATUS_AKTIF]),
        ];

        $this->seedStores($owners);
        $this->seedProducts();
    }

    private function seedStores(array $owners): void
    {
        $stores = [
            ['key' => 'aurelia', 'nama_toko' => 'AURELIA Couture', 'owner' => 'elara', 'alamat' => 'Jl. Cihampelas No. 88, Bandung', 'deskripsi' => 'Koleksi couture eksklusif untuk acara spesial.', 'status' => Store::STATUS_PENDING],
            ['key' => 'terra', 'nama_toko' => 'TERRA Threads', 'owner' => 'julian', 'alamat' => 'Jl. Senopati No. 19, Jakarta', 'deskripsi' => 'Pakaian ramah lingkungan dari bahan daur ulang.', 'status' => Store::STATUS_PENDING],
            ['key' => 'velvetine', 'nama_toko' => 'VELVETINE Co.', 'owner' => 'maya', 'alamat' => 'Jl. Braga No. 5, Bandung', 'deskripsi' => 'Velvet dan satin untuk penampilan elegan.', 'status' => Store::STATUS_NONAKTIF],
            ['key' => 'orla', 'nama_toko' => 'ORLA Collective', 'owner' => 'sofia', 'alamat' => 'Jl. Malioboro No. 44, Yogyakarta', 'deskripsi' => 'Kolektif lokal dengan sentuhan etnik modern.', 'status' => Store::STATUS_DITOLAK, 'alasan' => 'Dokumen legalitas usaha belum lengkap. Silakan lengkapi NIB dan ktp pemilik lalu ajukan ulang.'],
        ];

        foreach ($stores as $data) {
            Store::updateOrCreate(
                ['nama_toko' => $data['nama_toko']],
                [
                    'owner_id' => $owners[$data['owner']]->user_id,
                    'deskripsi' => $data['deskripsi'],
                    'alamat' => $data['alamat'],
                    'nomor_telepon' => '08'.random_int(1111111111, 9999999999),
                    'status' => $data['status'],
                    'alasan_penolakan' => $data['alasan'] ?? null,
                ]
            );
        }
    }

    private function seedProducts(): void
    {
        $products = [
            ['store' => 'LUNARA Fashion', 'nama_produk' => 'Blazer Kaftan Edisi Terbatas', 'kategori' => 'Atasan', 'harga_dasar' => 649000, 'status' => Product::STATUS_PENDING],
            ['store' => 'NOIRÉ Studio', 'nama_produk' => 'Utility Vest Linen', 'kategori' => 'Kemeja', 'harga_dasar' => 359000, 'status' => Product::STATUS_PENDING],
            ['store' => 'KAYANA Apparel', 'nama_produk' => 'Cargo Pants Techwear', 'kategori' => 'Celana', 'harga_dasar' => 429000, 'status' => Product::STATUS_PENDING],
            ['store' => 'Velvet Closet', 'nama_produk' => 'Sling Bag Kulit Vegan', 'kategori' => 'Tote Bag', 'harga_dasar' => 249000, 'status' => Product::STATUS_PENDING],
            ['store' => 'MAÉVA House', 'nama_produk' => 'Set Gamis Syar\'i Ceruty', 'kategori' => 'Gamis', 'harga_dasar' => 499000, 'status' => Product::STATUS_PENDING],
            ['store' => 'LUNARA Fashion', 'nama_produk' => 'Hoodie Inspired Brand X', 'kategori' => 'Jaket & Hoodie', 'harga_dasar' => 389000, 'status' => Product::STATUS_DITOLAK, 'alasan' => 'Produk menggunakan nama dan logo merek pihak lain sehingga melanggar ketentuan moderasi Raliva. Mohon ganti nama dan desain tanpa elemen merek tersebut.'],
        ];

        foreach ($products as $data) {
            $storeId = Store::where('nama_toko', $data['store'])->value('store_id');

            if (! $storeId) {
                continue;
            }

            $category = Category::where('nama_kategori', $data['kategori'])->firstOrFail();

            $product = Product::updateOrCreate(
                ['nama_produk' => $data['nama_produk'], 'store_id' => $storeId],
                [
                    'category_id' => $category->category_id,
                    'deskripsi' => $data['nama_produk'].' dari '.$data['store'].'.',
                    'harga_dasar' => $data['harga_dasar'],
                    'tipe_produk' => Product::TIPE_REGULAR,
                    'status' => $data['status'],
                    'alasan_penolakan' => $data['alasan'] ?? null,
                ]
            );

            ProductVariant::updateOrCreate(
                ['sku' => 'SKU-'.strtoupper(substr(md5($data['store'].$data['nama_produk']), 0, 8)).'-1'],
                [
                    'product_id' => $product->product_id,
                    'warna' => 'Hitam',
                    'ukuran' => 'M',
                    'harga' => $data['harga_dasar'],
                    'status' => 'aktif',
                ]
            );
        }
    }
}
