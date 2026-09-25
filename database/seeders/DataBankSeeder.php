<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\PaymentMethod;
use App\Models\PlatformBankAccount;
use Illuminate\Database\Seeder;

class DataBankSeeder extends Seeder
{
    /**
     * Master data bank, metode pembayaran, dan akun pembayaran platform.
     * Menggabungkan BankSeeder, PaymentMethodSeeder, dan PlatformPaymentAccountSeeder.
     * Idempotent — aman dijalankan berulang.
     */
    public function run(): void
    {
        // ── 1. Bank master ──────────────────────────────────────────
        $banks = [
            ['kode_bank' => 'bca',      'nama_bank' => 'Bank Central Asia (BCA)',    'rekening' => '9876543210',    'pemilik' => 'RALIVA Fashion'],
            ['kode_bank' => 'bri',      'nama_bank' => 'Bank Rakyat Indonesia (BRI)', 'rekening' => '1234567890123', 'pemilik' => 'RALIVA Fashion'],
            ['kode_bank' => 'mandiri',  'nama_bank' => 'Bank Mandiri',                'rekening' => '9871234567',    'pemilik' => 'RALIVA Fashion'],
            ['kode_bank' => 'bni',      'nama_bank' => 'Bank Negara Indonesia (BNI)', 'rekening' => '7654321098',    'pemilik' => 'RALIVA Fashion'],
            ['kode_bank' => 'bsi',      'nama_bank' => 'Bank Syariah Indonesia (BSI)', 'rekening' => '8765432109',    'pemilik' => 'RALIVA Fashion'],
        ];

        $bankIds = [];
        foreach ($banks as $bank) {
            $b = Bank::updateOrCreate(
                ['kode_bank' => $bank['kode_bank']],
                ['nama_bank' => $bank['nama_bank'], 'status' => 'aktif']
            );
            $bankIds[$bank['kode_bank']] = $b->bank_id;
        }

        // ── 2. Payment methods (meta) ────────────────────────────────
        $methods = [
            ['kode_metode' => PaymentMethod::KODE_QRIS,          'nama_metode' => 'QRIS',          'batas_waktu_menit' => 5],
            ['kode_metode' => PaymentMethod::KODE_EWALLET,       'nama_metode' => 'E-Wallet',      'batas_waktu_menit' => 5],
            ['kode_metode' => PaymentMethod::KODE_BANK_TRANSFER, 'nama_metode' => 'Bank Transfer', 'batas_waktu_menit' => 30],
            ['kode_metode' => PaymentMethod::KODE_SALDO_AKUN,    'nama_metode' => 'Saldo Akun',    'batas_waktu_menit' => 0],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['kode_metode' => $method['kode_metode']],
                [
                    'nama_metode' => $method['nama_metode'],
                    'batas_waktu_menit' => $method['batas_waktu_menit'],
                    'status' => PaymentMethod::STATUS_AKTIF,
                ]
            );
        }

        // ── 3. QRIS account ──────────────────────────────────────────
        PlatformBankAccount::updateOrCreate(
            ['jenis' => PlatformBankAccount::JENIS_QRIS, 'kode' => 'qris'],
            [
                'bank_id' => null,
                'nama' => 'QRIS RALIVA',
                'deskripsi' => 'Scan kode QR dengan aplikasi apa pun (GoPay, OVO, Dana, ShopeePay, m-Banking).',
                'nomor_rekening' => null,
                'nama_pemilik' => 'RALIVA Fashion',
                'file_gambar' => null,
                'urutan' => 1,
                'status' => PlatformBankAccount::STATUS_AKTIF,
            ]
        );

        // ── 4. E-Wallet accounts ─────────────────────────────────────
        $ewallets = [
            ['nama' => 'DANA',       'kode' => 'dana',       'nomor_rekening' => '081234567890', 'file_gambar' => 'images/E-Wallet/dana.png',      'urutan' => 1],
            ['nama' => 'GoPay',     'kode' => 'gopay',      'nomor_rekening' => '081234567891', 'file_gambar' => 'images/E-Wallet/gopay.jpg',     'urutan' => 2],
            ['nama' => 'OVO',       'kode' => 'ovo',        'nomor_rekening' => '081234567892', 'file_gambar' => 'images/E-Wallet/ovo.png',       'urutan' => 3],
            ['nama' => 'ShopeePay', 'kode' => 'shopeepay',  'nomor_rekening' => '081234567893', 'file_gambar' => 'images/E-Wallet/shoopepay.jfif','urutan' => 4],
            ['nama' => 'LinkAja',   'kode' => 'linkaja',    'nomor_rekening' => '081234567894', 'file_gambar' => null, 'urutan' => 5],
            ['nama' => 'Jenius',    'kode' => 'jenius',     'nomor_rekening' => '081234567895', 'file_gambar' => null, 'urutan' => 6],
        ];

        foreach ($ewallets as $wallet) {
            PlatformBankAccount::updateOrCreate(
                ['jenis' => PlatformBankAccount::JENIS_EWALLET, 'kode' => $wallet['kode']],
                [
                    'bank_id' => null,
                    'nama' => $wallet['nama'],
                    'deskripsi' => 'Transfer ke saldo '.$wallet['nama'].' di bawah ini, lalu unggah buktinya.',
                    'nomor_rekening' => $wallet['nomor_rekening'],
                    'nama_pemilik' => 'RALIVA Fashion',
                    'file_gambar' => $wallet['file_gambar'],
                    'urutan' => $wallet['urutan'],
                    'status' => PlatformBankAccount::STATUS_AKTIF,
                ]
            );
        }

        // ── 5. Bank Transfer accounts (dari data bank di langkah 1) ──
        $urutan = 1;
        foreach ($banks as $bank) {
            PlatformBankAccount::updateOrCreate(
                ['jenis' => PlatformBankAccount::JENIS_BANK_TRANSFER, 'bank_id' => $bankIds[$bank['kode_bank']]],
                [
                    'nama' => 'Bank '.strtoupper($bank['kode_bank']),
                    'kode' => $bank['kode_bank'],
                    'deskripsi' => 'Transfer ke rekening Bank '.strtoupper($bank['kode_bank']).' di bawah ini, lalu unggah buktinya.',
                    'nomor_rekening' => $bank['rekening'],
                    'nama_pemilik' => $bank['pemilik'],
                    'file_gambar' => in_array($bank['kode_bank'], ['bca', 'bri', 'mandiri', 'bni'], true) ? 'images/Bank/'.$bank['kode_bank'].'.png' : null,
                    'urutan' => $urutan++,
                    'status' => PlatformBankAccount::STATUS_AKTIF,
                ]
            );
        }
    }
}
