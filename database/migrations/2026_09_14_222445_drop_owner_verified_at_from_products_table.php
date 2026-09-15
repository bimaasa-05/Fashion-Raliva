<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom owner_verified_at tidak lagi dipakai setelah moderasi produk
     * pindah langsung ke Super Admin (tanpa langkah persetujuan Owner).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('owner_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('owner_verified_at')->nullable()->after('alasan_penolakan');
        });
    }
};