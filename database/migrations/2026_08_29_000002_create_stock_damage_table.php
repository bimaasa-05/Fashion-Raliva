<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_damage', function (Blueprint $table) {
            $table->bigIncrements('stock_damage_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('jumlah_rusak');
            $table->text('alasan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('created_at')->nullable();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('dibuat_oleh')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_damage');
    }
};
