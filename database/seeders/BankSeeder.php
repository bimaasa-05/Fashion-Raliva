<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['kode_bank' => 'bca', 'nama_bank' => 'Bank Central Asia (BCA)'],
            ['kode_bank' => 'bri', 'nama_bank' => 'Bank Rakyat Indonesia (BRI)'],
            ['kode_bank' => 'bni', 'nama_bank' => 'Bank Negara Indonesia (BNI)'],
            ['kode_bank' => 'mandiri', 'nama_bank' => 'Bank Mandiri'],
            ['kode_bank' => 'bsi', 'nama_bank' => 'Bank Syariah Indonesia (BSI)'],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['kode_bank' => $bank['kode_bank']],
                ['nama_bank' => $bank['nama_bank'], 'status' => 'aktif']
            );
        }
    }
}
