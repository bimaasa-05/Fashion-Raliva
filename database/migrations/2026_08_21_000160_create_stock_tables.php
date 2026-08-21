<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('stock_movement_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->string('tipe_pergerakan', 30);
            $table->unsignedInteger('jumlah');
            $table->string('sumber_tipe', 30)->nullable();
            $table->unsignedBigInteger('sumber_id')->nullable();
            $table->text('alasan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('created_at')->nullable();

            $table->index(['sumber_tipe', 'sumber_id']);
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('dibuat_oleh')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->bigIncrements('stock_transfer_id');
            $table->unsignedBigInteger('from_warehouse_id');
            $table->unsignedBigInteger('to_warehouse_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('status', 30)->default('requested');
            $table->dateTime('diminta_pada');
            $table->dateTime('diterima_pada')->nullable();
            $table->timestamps();

            $table->foreign('from_warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('to_warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('requested_by')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('approved_by')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->bigIncrements('stock_transfer_item_id');
            $table->unsignedBigInteger('stock_transfer_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('jumlah');
            $table->timestamps();

            $table->foreign('stock_transfer_id')->references('stock_transfer_id')->on('stock_transfers')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('stock_movements');
    }
};
