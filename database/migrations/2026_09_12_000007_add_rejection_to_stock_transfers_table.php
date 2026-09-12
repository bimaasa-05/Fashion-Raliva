<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('status');
            $table->dateTime('dibatalkan_pada')->nullable()->after('diterima_pada');
        });
    }

    public function down(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'dibatalkan_pada']);
        });
    }
};