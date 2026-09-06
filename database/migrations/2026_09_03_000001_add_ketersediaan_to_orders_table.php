<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_ketersediaan', 30)->nullable()->after('status');
            $table->text('catatan_gudang')->nullable()->after('status_ketersediaan');
            $table->timestamp('dicek_gudang_pada')->nullable()->after('catatan_gudang');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status_ketersediaan', 'catatan_gudang', 'dicek_gudang_pada']);
        });
    }
};
