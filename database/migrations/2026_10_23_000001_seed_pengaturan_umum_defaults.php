<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $defaults = [
            'nama_platform' => 'Raliva',
            'email_support' => 'support@raliva.com',
            'whatsapp_support' => '',
            'mode_maintenance' => '0',
        ];

        $rows = [];

        foreach ($defaults as $kunci => $nilai) {
            $rows[] = [
                'kunci' => $kunci,
                'nilai' => $nilai,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Idempoten: jangan menimpa nilai yang sudah diubah SuperAdmin.
        DB::table('settings')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        // Sengaja no-op: menghapus setting berisiko menghilangkan konfigurasi admin.
    }
};
