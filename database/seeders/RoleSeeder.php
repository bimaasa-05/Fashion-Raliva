<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nama_role' => Role::SUPER_ADMIN,
                'deskripsi' => 'Pengelola platform Raliva dengan akses penuh ke seluruh modul platform.',
            ],
            [
                'nama_role' => Role::OWNER,
                'deskripsi' => 'Pemilik toko. Mengelola bisnis tokonya tanpa hak global platform.',
            ],
            [
                'nama_role' => Role::ADMIN,
                'deskripsi' => 'Petugas operasional toko yang ditugaskan oleh Owner (scope per toko).',
            ],
            [
                'nama_role' => Role::PRODUKSI,
                'deskripsi' => 'Petugas produksi yang memproses production order (scope per toko).',
            ],
            [
                'nama_role' => Role::GUDANG,
                'deskripsi' => 'Petugas gudang yang mengelola stok dan mutasi barang (scope per gudang).',
            ],
            [
                'nama_role' => Role::CUSTOMER,
                'deskripsi' => 'Pembeli yang berbelanja di marketplace Raliva.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nama_role' => $role['nama_role']],
                ['deskripsi' => $role['deskripsi'], 'status' => 'aktif']
            );
        }
    }
}
