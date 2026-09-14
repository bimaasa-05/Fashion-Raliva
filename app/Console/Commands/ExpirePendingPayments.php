<?php

namespace App\Console\Commands;

use App\Support\PaymentExpiry;
use Illuminate\Console\Command;

class ExpirePendingPayments extends Command
{
    protected $signature = 'payment:expire';

    protected $description = 'Batalkan otomatis pembayaran pending yang melewati batas_waktu';

    public function handle(): int
    {
        $count = PaymentExpiry::expireOverdue();

        if ($count > 0) {
            $this->info("{$count} pembayaran kedaluwarsa dibatalkan.");
        } else {
            $this->line('Tidak ada pembayaran yang perlu di-expire.');
        }

        return self::SUCCESS;
    }
}