<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_order_bahan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('bahan_id')->nullable();
            $table->foreign('bahan_id')->references('bahan_id')->on('bahan_produksi')->nullOnDelete();
            $table->string('nama_bahan', 150);
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan', 20);
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_order_bahan');
    }
};
