<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('metode_fulfillment', 10)->default('diantar')->after('tipe_pesanan');
        });

        // Backfill dari makna lama: online = diantar, offline = ambil.
        DB::table('orders')->where('tipe_pesanan', 'online')->update(['metode_fulfillment' => 'diantar']);
        DB::table('orders')->where('tipe_pesanan', 'offline')->update(['metode_fulfillment' => 'ambil']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('metode_fulfillment');
        });
    }
};
