<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sa@gmail.com'],
            [
                'nama_lengkap' => 'Super Admin Raliva',
                'password' => Hash::make('123'),
                'role_id' => Role::where('nama_role', Role::SUPER_ADMIN)->value('role_id'),
                'nomor_telepon' => '081100000000',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
        // keep legacy superadmin@ for tests/backward compat
        User::updateOrCreate(
            ['email' => 'superadmin@raliva.test'],
            [
                'nama_lengkap' => 'Super Admin Raliva',
                'password' => Hash::make('123'),
                'role_id' => Role::where('nama_role', Role::SUPER_ADMIN)->value('role_id'),
                'nomor_telepon' => '081100000000',
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
    }
}
