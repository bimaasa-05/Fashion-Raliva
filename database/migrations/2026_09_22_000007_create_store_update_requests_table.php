<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_update_requests', function (Blueprint $table) {
            $table->id('store_update_request_id');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->string('nama_toko', 150);
            $table->string('kategori', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('alamat', 500);
            $table->string('nomor_telepon', 20);
            $table->string('status', 20)->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();
            $table->index(['store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_update_requests');
    }
};
