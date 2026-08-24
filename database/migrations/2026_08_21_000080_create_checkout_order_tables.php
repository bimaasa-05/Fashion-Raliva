<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->bigIncrements('checkout_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('total_pajak', 15, 2)->default(0);
            $table->decimal('biaya_layanan', 15, 2)->default(0);
            $table->decimal('total_ongkir', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('checkout_id');
            $table->unsignedBigInteger('store_id');
            $table->string('nomor_order', 100)->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('total_pajak', 15, 2)->default(0);
            $table->decimal('biaya_layanan', 15, 2)->default(0);
            $table->decimal('total_ongkir', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->string('status', 30)->default('pending_payment');
            $table->timestamps();

            $table->foreign('checkout_id')->references('checkout_id')->on('checkouts')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->string('nama_produk_snapshot', 200);
            $table->decimal('harga_snapshot', 15, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('checkouts');
    }
};
