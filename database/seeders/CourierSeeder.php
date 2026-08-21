<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            ['kode_kurir' => 'jne', 'nama_kurir' => 'JNE Express'],
            ['kode_kurir' => 'jnt', 'nama_kurir' => 'J&T Express'],
            ['kode_kurir' => 'sicepat', 'nama_kurir' => 'SiCepat Ekspres'],
            ['kode_kurir' => 'anteraja', 'nama_kurir' => 'AnterAja'],
            ['kode_kurir' => 'pos', 'nama_kurir' => 'POS Indonesia'],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(
                ['kode_kurir' => $courier['kode_kurir']],
                ['nama_kurir' => $courier['nama_kurir'], 'status' => 'aktif']
            );
        }
    }
}
