<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\PaymentMethodAccount;
use Illuminate\Database\Seeder;

class PaymentMethodAccountSeeder extends Seeder
{
    public function run(): void
    {
        $qris = PaymentMethod::where('kode_metode', PaymentMethod::KODE_QRIS)->value('payment_method_id');
        $ewallet = PaymentMethod::where('kode_metode', PaymentMethod::KODE_EWALLET)->value('payment_method_id');
        $bank = PaymentMethod::where('kode_metode', PaymentMethod::KODE_BANK_TRANSFER)->value('payment_method_id');

        $accounts = [
            // QRIS — satu akun dengan gambar QR
            [
                'payment_method_id' => $qris,
                'nama' => 'QRIS RALIVA',
                'kode' => 'qris',
                'deskripsi' => 'Scan kode QR dengan aplikasi apa pun (GoPay, OVO, Dana, ShopeePay, m-Banking).',
                'nomor_rekening' => null,
                'nama_pemilik' => 'RALIVA Fashion',
                'file_gambar' => 'payment_methods/qris-raliva.png',
                'urutan' => 1,
            ],

            // E-Wallet
            ['payment_method_id' => $ewallet, 'nama' => 'DANA', 'kode' => 'dana', 'deskripsi' => 'Transfer ke saldo DANA di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567890', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/dana.png', 'urutan' => 1],
            ['payment_method_id' => $ewallet, 'nama' => 'GoPay', 'kode' => 'gopay', 'deskripsi' => 'Transfer ke saldo GoPay di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567891', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/gopay.png', 'urutan' => 2],
            ['payment_method_id' => $ewallet, 'nama' => 'OVO', 'kode' => 'ovo', 'deskripsi' => 'Transfer ke saldo OVO di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567892', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/ovo.png', 'urutan' => 3],
            ['payment_method_id' => $ewallet, 'nama' => 'ShopeePay', 'kode' => 'shopeepay', 'deskripsi' => 'Transfer ke saldo ShopeePay di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567893', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/shopeepay.png', 'urutan' => 4],
            ['payment_method_id' => $ewallet, 'nama' => 'LinkAja', 'kode' => 'linkaja', 'deskripsi' => 'Transfer ke saldo LinkAja di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567894', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/linkaja.png', 'urutan' => 5],
            ['payment_method_id' => $ewallet, 'nama' => 'Jenius', 'kode' => 'jenius', 'deskripsi' => 'Transfer ke saldo Jenius di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '081234567895', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/jenius.png', 'urutan' => 6],

            // Bank Transfer
            ['payment_method_id' => $bank, 'nama' => 'Bank BCA', 'kode' => 'bca', 'deskripsi' => 'Transfer ke rekening BCA di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '9876543210', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/bca.png', 'urutan' => 1],
            ['payment_method_id' => $bank, 'nama' => 'Bank BRI', 'kode' => 'bri', 'deskripsi' => 'Transfer ke rekening BRI di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '1234567890123', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/bri.png', 'urutan' => 2],
            ['payment_method_id' => $bank, 'nama' => 'Bank Mandiri', 'kode' => 'mandiri', 'deskripsi' => 'Transfer ke rekening Mandiri di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '9871234567', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/mandiri.png', 'urutan' => 3],
            ['payment_method_id' => $bank, 'nama' => 'Bank BNI', 'kode' => 'bni', 'deskripsi' => 'Transfer ke rekening BNI di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '7654321098', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/bni.png', 'urutan' => 4],
            ['payment_method_id' => $bank, 'nama' => 'Bank BSI', 'kode' => 'bsi', 'deskripsi' => 'Transfer ke rekening BSI di bawah ini, lalu unggah buktinya.', 'nomor_rekening' => '8765432109', 'nama_pemilik' => 'RALIVA Fashion', 'file_gambar' => 'payment_methods/bsi.png', 'urutan' => 5],
        ];

        foreach ($accounts as $account) {
            PaymentMethodAccount::updateOrCreate(
                ['payment_method_id' => $account['payment_method_id'], 'kode' => $account['kode']],
                $account
            );
        }
    }
}