<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->bigIncrements('production_order_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('target_warehouse_id');
            $table->string('nomor_produksi', 100)->unique();
            $table->string('prioritas', 20)->default('normal');
            $table->string('status', 30)->default('requested');
            $table->text('catatan')->nullable();
            $table->dateTime('dimulai_pada')->nullable();
            $table->dateTime('selesai_pada')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('requested_by')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('assigned_to')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('target_warehouse_id')->references('warehouse_id')->on('warehouses')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('production_order_items', function (Blueprint $table) {
            $table->bigIncrements('production_order_item_id');
            $table->unsignedBigInteger('production_order_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('jumlah_diminta');
            $table->timestamps();

            $table->foreign('production_order_id')->references('production_order_id')->on('production_orders')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('production_results', function (Blueprint $table) {
            $table->bigIncrements('production_result_id');
            $table->unsignedBigInteger('production_order_id');
            $table->unsignedInteger('jumlah_diproduksi');
            $table->unsignedInteger('jumlah_gagal')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('production_order_id')->references('production_order_id')->on('production_orders')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('quality_checks', function (Blueprint $table) {
            $table->bigIncrements('quality_check_id');
            $table->unsignedBigInteger('production_order_id');
            $table->unsignedBigInteger('checked_by');
            $table->unsignedInteger('jumlah_lulus');
            $table->unsignedInteger('jumlah_gagal')->default(0);
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->dateTime('diperiksa_pada');
            $table->timestamps();

            $table->foreign('production_order_id')->references('production_order_id')->on('production_orders')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('checked_by')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
        Schema::dropIfExists('production_results');
        Schema::dropIfExists('production_order_items');
        Schema::dropIfExists('production_orders');
    }
};
