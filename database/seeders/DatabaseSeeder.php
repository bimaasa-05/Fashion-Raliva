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
            DataBankSeeder::class,
            CourierSeeder::class,
            CitySeeder::class,
            CategorySeeder::class,
            StoreCategorySeeder::class,
            StoreCategorySizeSeeder::class,
            SuperAdminSeeder::class,
            GudangDemoSeeder::class,
            UserSeeder::class,
            OwnerSeeder::class,
            ProductSeeder::class,
            PermintaanProduksiSeeder::class,
            AdminDemoSeeder::class,
            WarehouseStaffPermissionSeeder::class,
            HelpCenterSeeder::class,
            LegalDocumentSeeder::class,
            BarangKeluarSeeder::class,
        ]);
    }
}
