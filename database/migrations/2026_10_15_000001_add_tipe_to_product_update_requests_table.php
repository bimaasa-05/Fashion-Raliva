<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_update_requests', function (Blueprint $table) {
            // Jenis pengajuan: ubah (default, perilaku lama) atau hapus.
            $table->string('tipe', 20)->default('ubah')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('product_update_requests', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
