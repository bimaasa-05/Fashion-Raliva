<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStaff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('role_id', 'nama_role');

        $owner = User::updateOrCreate(
            ['email' => 'owner@raliva.test'],
            [
                'nama_lengkap' => 'Bima Prasetya',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::OWNER],
                'nomor_telepon' => '081234567890',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        $store = Store::updateOrCreate(
            ['nama_toko' => 'Raliva Atelier Jakarta'],
            [
                'owner_id' => $owner->user_id,
                'deskripsi' => 'Atelier fashion premium Raliva di Jakarta.',
                'alamat' => 'Jl. Sudirman No. 10, Jakarta',
                'nomor_telepon' => '0215551234',
                'status' => Store::STATUS_AKTIF,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@raliva.test'],
            [
                'nama_lengkap' => 'Sinta Maharani',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::ADMIN],
                'nomor_telepon' => '081234567891',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        StoreStaff::updateOrCreate(
            ['user_id' => $admin->user_id, 'store_id' => $store->store_id],
            [
                'tanggal_penugasan' => now(),
                'status' => 'aktif',
            ]
        );

        $gudang = User::updateOrCreate(
            ['email' => 'gudang@raliva.test'],
            [
                'nama_lengkap' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::GUDANG],
                'nomor_telepon' => '081234567892',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        $warehouse = Warehouse::updateOrCreate(
            ['nama_gudang' => 'Gudang Utama Bandung'],
            [
                'store_id' => $store->store_id,
                'alamat' => 'Jl. Asia Afrika No. 20, Bandung',
                'nomor_telepon' => '0225551234',
                'status' => Warehouse::STATUS_AKTIF,
            ]
        );

        WarehouseStaff::updateOrCreate(
            ['user_id' => $gudang->user_id, 'warehouse_id' => $warehouse->warehouse_id],
            [
                'tanggal_penugasan' => now(),
                'status' => 'aktif',
            ]
        );

        User::updateOrCreate(
            ['email' => 'produksi@raliva.test'],
            [
                'nama_lengkap' => 'Rini Kusuma',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::PRODUKSI],
                'nomor_telepon' => '081234567893',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@raliva.test'],
            [
                'nama_lengkap' => 'Jane Doe',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::CUSTOMER],
                'nomor_telepon' => '081234567894',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
    }
}
