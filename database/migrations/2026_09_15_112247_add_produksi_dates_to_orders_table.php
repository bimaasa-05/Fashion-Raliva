<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->datetime('tgl_mulai_produksi')->nullable()->after('status');
            $table->datetime('tgl_berakhir_produksi')->nullable()->after('tgl_mulai_produksi');
            $table->datetime('produksi_dimulai_pada')->nullable()->after('tgl_berakhir_produksi');
            $table->text('produksi_catatan_tolak')->nullable()->after('produksi_dimulai_pada');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tgl_mulai_produksi', 'tgl_berakhir_produksi', 'produksi_dimulai_pada', 'produksi_catatan_tolak']);
        });
    }
};
