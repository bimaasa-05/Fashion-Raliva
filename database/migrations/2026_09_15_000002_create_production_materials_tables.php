<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_materials', function (Blueprint $table) {
            $table->bigIncrements('production_material_id');
            $table->unsignedBigInteger('store_id');
            $table->string('nama_bahan', 150);
            $table->string('satuan', 30);
            $table->decimal('stok_sekarang', 12, 2)->default(0);
            $table->decimal('minimal_stok', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('material_movements', function (Blueprint $table) {
            $table->bigIncrements('material_movement_id');
            $table->unsignedBigInteger('production_material_id');
            $table->string('tipe', 20);
            $table->decimal('jumlah', 12, 2);
            $table->decimal('saldo_akhir', 12, 2);
            $table->unsignedBigInteger('production_order_id')->nullable();
            $table->text('alasan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('production_material_id')->references('production_material_id')->on('production_materials')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('production_order_id')->references('production_order_id')->on('production_orders')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('dibuat_oleh')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
        });

        Schema::create('production_order_materials', function (Blueprint $table) {
            $table->bigIncrements('production_order_material_id');
            $table->unsignedBigInteger('production_order_id');
            $table->unsignedBigInteger('production_material_id');
            $table->decimal('jumlah_pakai', 12, 2);
            $table->timestamps();

            $table->foreign('production_order_id')->references('production_order_id')->on('production_orders')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('production_material_id')->references('production_material_id')->on('production_materials')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_order_materials');
        Schema::dropIfExists('material_movements');
        Schema::dropIfExists('production_materials');
    }
};