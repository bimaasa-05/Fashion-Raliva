<?php

namespace App\Console\Commands;

use App\Support\OrderAutoComplete;
use Illuminate\Console\Command;

class AutoCompleteShippedOrders extends Command
{
    protected $signature = 'order:auto-complete';

    protected $description = 'Selesaikan otomatis order dikirim/offline yang melewati batas konfirmasi';

    public function handle(): int
    {
        $count = OrderAutoComplete::selesaikanOtomatis();

        if ($count > 0) {
            $this->info("{$count} pesanan diselesaikan otomatis.");
        } else {
            $this->line('Tidak ada pesanan yang perlu diselesaikan otomatis.');
        }

        return self::SUCCESS;
    }
}