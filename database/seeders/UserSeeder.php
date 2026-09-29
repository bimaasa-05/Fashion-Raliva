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
        // Akun demo legacy (o/a/g/p/c@gmail.com) dihapus 2026-09-25 agar tidak
        // duplikat dengan akun @raliva.test. Akun demo dibuat oleh
        // RalivaDemoSeeder (@raliva.test) dan OwnerSeeder (owner@raliva.test).
    }
}
