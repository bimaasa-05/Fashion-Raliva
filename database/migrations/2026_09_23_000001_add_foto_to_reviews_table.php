<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->after('ulasan');
        });

        // Backfill: ulasan lama yang tertahan dimoderasi langsung diaktifkan.
        DB::table('reviews')->where('status', 'dimoderasi')->update(['status' => 'aktif']);
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
