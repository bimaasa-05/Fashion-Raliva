<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class ReactivateExpiredSuspensions extends Command
{
    protected $signature = 'store:auto-reactivate';

    protected $description = 'Aktifkan kembali toko yang ditangguhkan sementara setelah melewati batas waktu';

    public function handle(): int
    {
        $reactivated = Store::autoReactivateExpired();

        if ($reactivated === 0) {
            $this->line('Tidak ada toko yang perlu diaktifkan kembali.');
        } else {
            $this->info("{$reactivated} toko diaktifkan kembali.");
        }

        return self::SUCCESS;
    }
}