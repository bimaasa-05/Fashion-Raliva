<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya ubah nilai lama; jangan menimpa email yang sudah disesuaikan admin.
        DB::table('settings')
            ->where('kunci', 'email_support')
            ->where('nilai', 'support@raliva.com')
            ->update([
                'nilai' => 'support@nexprint.com',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Sengaja no-op: perubahan email adalah migrasi data satu arah.
    }
};
