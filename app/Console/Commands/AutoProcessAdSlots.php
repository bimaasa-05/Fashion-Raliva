<?php

namespace App\Console\Commands;

use App\Models\AdSlot;
use Illuminate\Console\Command;

class AutoProcessAdSlots extends Command
{
    protected $signature = 'ad:auto-process';

    protected $description = 'Aktifkan iklan terjadwal saat tanggal mulai tiba dan nonaktifkan iklan yang melewati tanggal selesai';

    public function handle(): int
    {
        $changed = AdSlot::autoProcess();

        if ($changed > 0) {
            $this->info("{$changed} iklan diproses (aktif/nonaktif).");
        } else {
            $this->line('Tidak ada iklan yang perlu diubah statusnya.');
        }

        return self::SUCCESS;
    }
}
