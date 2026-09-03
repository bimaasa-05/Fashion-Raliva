<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('role_id', 'nama_role');

        $owner = User::updateOrCreate(
            ['email' => 'o@gmail.com'],
            [
                'nama_lengkap' => 'Bima Prasetya',
                'password' => Hash::make('123'),
                'role_id' => $roles[Role::OWNER],
                'nomor_telepon' => '081234567890',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // Catatan: toko TIDAK dibuat otomatis di sini agar owner default
        // (setelah `migrate:fresh --seed`) belum memiliki toko.
        // Data toko + demo lengkap diisi lewat `OwnerSeeder`
        // (jalankan: php artisan db:seed --class=OwnerSeeder).

        $admin = User::updateOrCreate(
            ['email' => 'a@gmail.com'],
            [
                'nama_lengkap' => 'Sinta Maharani',
                'password' => Hash::make('123'),
                'role_id' => $roles[Role::ADMIN],
                'nomor_telepon' => '081234567891',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // Penugasan admin ke toko + pembuatan warehouse ditangani di OwnerSeeder
        // (karena membutuhkan store yang baru dibuat di sana).

        $gudang = User::updateOrCreate(
            ['email' => 'g@gmail.com'],
            [
                'nama_lengkap' => 'Andi Pratama',
                'password' => Hash::make('123'),
                'role_id' => $roles[Role::GUDANG],
                'nomor_telepon' => '081234567892',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        User::updateOrCreate(
            ['email' => 'p@gmail.com'],
            [
                'nama_lengkap' => 'Rini Kusuma',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::PRODUKSI],
                'nomor_telepon' => '081234567893',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // Tambahan user Produksi

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

        // Tambahan user Customer
        User::updateOrCreate(
            ['email' => 'customer2@raliva.test'],
            [
                'nama_lengkap' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::CUSTOMER],
                'nomor_telepon' => '081234567898',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
        User::updateOrCreate(
            ['email' => 'customer3@raliva.test'],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::CUSTOMER],
                'nomor_telepon' => '081234567899',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );

        // Tambahan Owner
        User::updateOrCreate(
            ['email' => 'owner2@raliva.test'],
            [
                'nama_lengkap' => 'Rina Wijaya',
                'password' => Hash::make('password'),
                'role_id' => $roles[Role::OWNER],
                'nomor_telepon' => '081234567900',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
    }
}
