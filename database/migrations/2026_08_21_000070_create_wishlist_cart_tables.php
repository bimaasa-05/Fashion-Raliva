<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('wishlist_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->bigIncrements('wishlist_item_id');
            $table->unsignedBigInteger('wishlist_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamp('created_at')->nullable();

            $table->unique(['wishlist_id', 'product_id']);
            $table->foreign('wishlist_id')->references('wishlist_id')->on('wishlists')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('cart_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('cart_item_id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('quantity');
            $table->decimal('harga_snapshot', 15, 2);
            $table->timestamps();

            $table->unique(['cart_id', 'product_variant_id']);
            $table->foreign('cart_id')->references('cart_id')->on('carts')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
    }
};
