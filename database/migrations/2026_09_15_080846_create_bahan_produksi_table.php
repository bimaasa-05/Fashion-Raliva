<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_produksi', function (Blueprint $table) {
            $table->bigIncrements('bahan_id');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->string('nama_bahan', 150);
            $table->string('kategori', 50)->default('lainnya');
            $table->string('satuan', 20)->default('meter');
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_produksi');
    }
};
