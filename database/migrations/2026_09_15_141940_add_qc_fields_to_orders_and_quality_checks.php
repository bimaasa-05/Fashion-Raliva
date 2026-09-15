<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom QC di orders
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('jumlah_berhasil')->nullable()->after('produksi_catatan_tolak');
            $table->integer('jumlah_gagal')->nullable()->after('jumlah_berhasil');
            $table->datetime('tanggal_qc')->nullable()->after('jumlah_gagal');
            $table->datetime('tanggal_packing')->nullable()->after('tanggal_qc');
        });

        // Tambah order_id di quality_checks (link langsung ke orders)
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('production_order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['jumlah_berhasil', 'jumlah_gagal', 'tanggal_qc', 'tanggal_packing']);
        });
    }
};
