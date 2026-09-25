<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya perbaiki baris yang masih memakai path seeder lama (file tidak ada di disk).
        // Logo yang sudah diunggah manual via SA tidak disentuh.
        $map = [
            'payment_methods/qris-raliva.png' => null,
            'payment_methods/dana.png' => 'images/E-Wallet/dana.png',
            'payment_methods/gopay.png' => 'images/E-Wallet/gopay.jpg',
            'payment_methods/ovo.png' => 'images/E-Wallet/ovo.png',
            'payment_methods/shopeepay.png' => 'images/E-Wallet/shoopepay.jfif',
            'payment_methods/linkaja.png' => null,
            'payment_methods/jenius.png' => null,
            'payment_methods/bca.png' => 'images/Bank/bca.png',
            'payment_methods/bri.png' => 'images/Bank/bri.png',
            'payment_methods/mandiri.png' => 'images/Bank/mandiri.png',
            'payment_methods/bni.png' => 'images/Bank/bni.png',
            'payment_methods/bsi.png' => null,
        ];

        foreach ($map as $old => $new) {
            DB::table('platform_bank_accounts')
                ->where('file_gambar', $old)
                ->update(['file_gambar' => $new]);
        }
    }

    public function down(): void
    {
        // Tidak dikembalikan (logo diunggah manual via SA bersifat final).
    }
};
