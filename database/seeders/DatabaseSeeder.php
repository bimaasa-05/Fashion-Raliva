<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            BankSeeder::class,
            PaymentMethodSeeder::class,
            CourierSeeder::class,
            CategorySeeder::class,
            StoreCategorySeeder::class,
            SuperAdminSeeder::class,
            GudangDemoSeeder::class,
            UserSeeder::class,
            OwnerSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            WalletSeeder::class,
            PermintaanProduksiSeeder::class,
            AdminDemoSeeder::class,
            RalivaDemoSeeder::class,
            WarehouseStaffPermissionSeeder::class,
        ]);
    }
}
