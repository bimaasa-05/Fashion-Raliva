<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nilai era flat-Rp (>100) tidak bermakna sebagai persen → kembalikan ke 0 (gratis).
        DB::table('settings')
            ->where('kunci', 'biaya_penarikan_saldo')
            ->whereRaw('CAST(nilai AS DECIMAL(15,2)) > 100')
            ->update(['nilai' => '0']);
    }

    public function down(): void
    {
        // Tidak ada yang perlu dikembalikan.
    }
};
