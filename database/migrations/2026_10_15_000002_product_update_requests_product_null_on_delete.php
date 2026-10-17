<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengajuan hapus yang disetujui menghapus produknya; baris pengajuan
        // dipertahankan sebagai arsip (tampil "Produk dihapus") sehingga
        // product_id dibuat nullable + FK nullOnDelete (semula cascade).
        Schema::table('product_update_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        DB::statement('ALTER TABLE product_update_requests MODIFY product_id BIGINT UNSIGNED NULL');
        Schema::table('product_update_requests', function (Blueprint $table) {
            $table->foreign('product_id')->references('product_id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_update_requests', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('product_update_requests', function (Blueprint $table) {
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
        });
    }
};
