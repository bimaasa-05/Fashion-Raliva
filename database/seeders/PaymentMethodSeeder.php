<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['kode_metode' => PaymentMethod::KODE_QRIS, 'nama_metode' => 'QRIS', 'batas_waktu_menit' => 5],
            ['kode_metode' => PaymentMethod::KODE_EWALLET, 'nama_metode' => 'E-Wallet', 'batas_waktu_menit' => 5],
            ['kode_metode' => PaymentMethod::KODE_BANK_TRANSFER, 'nama_metode' => 'Bank Transfer', 'batas_waktu_menit' => 30],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['kode_metode' => $method['kode_metode']],
                [
                    'nama_metode' => $method['nama_metode'],
                    'batas_waktu_menit' => $method['batas_waktu_menit'],
                    'status' => 'aktif',
                ]
            );
        }
    }
}
