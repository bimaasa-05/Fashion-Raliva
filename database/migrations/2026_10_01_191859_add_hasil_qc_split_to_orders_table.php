<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Snapshot angka produksi (sebelum ditimpa QC) + angka QC terpisah,
            // agar "Hasil Produksi" vs "Hasil QC" bisa tampil berdampingan.
            $table->unsignedInteger('hasil_produksi_berhasil')->nullable()->after('jumlah_gagal');
            $table->unsignedInteger('hasil_produksi_gagal')->nullable()->after('hasil_produksi_berhasil');
            $table->unsignedInteger('hasil_qc_lulus')->nullable()->after('hasil_produksi_gagal');
            $table->unsignedInteger('hasil_qc_gagal')->nullable()->after('hasil_qc_lulus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['hasil_produksi_berhasil', 'hasil_produksi_gagal', 'hasil_qc_lulus', 'hasil_qc_gagal']);
        });
    }
};
