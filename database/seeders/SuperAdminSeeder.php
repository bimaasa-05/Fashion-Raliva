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
            ['email' => 'superadmin@raliva.test'],
            [
                'nama_lengkap' => 'Super Admin Raliva',
                'password' => Hash::make('password'),
                'role_id' => Role::where('nama_role', Role::SUPER_ADMIN)->value('role_id'),
                'email_verified_at' => now(),
                'status' => User::STATUS_AKTIF,
            ]
        );
    }
}
