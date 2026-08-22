<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('warehouse_id');
            $table->unsignedBigInteger('store_id');
            $table->string('nama_gudang', 150);
            $table->text('alamat');
            $table->string('nomor_telepon', 30)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('warehouse_staff', function (Blueprint $table) {
            $table->bigIncrements('warehouse_staff_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_penugasan')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->unique(['warehouse_id', 'user_id']);
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->bigIncrements('warehouse_stock_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('jumlah_stok')->default(0);
            $table->unsignedInteger('jumlah_direservasi')->default(0);
            $table->unsignedInteger('stok_minimum')->default(0);
            $table->timestamp('updated_at')->nullable();

            $table->unique(['warehouse_id', 'product_variant_id']);
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stocks');
        Schema::dropIfExists('warehouse_staff');
        Schema::dropIfExists('warehouses');
    }
};
