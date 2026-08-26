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
            SuperAdminSeeder::class,
            DemoRankingSeeder::class,
            SuperAdminDemoSeeder::class,
            TransaksiDemoSeeder::class,
        ]);
    }
}
