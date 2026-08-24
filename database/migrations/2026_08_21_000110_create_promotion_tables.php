<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('promotion_id');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('kode_promo', 100)->unique();
            $table->string('nama_promo', 150);
            $table->string('tipe_diskon', 20);
            $table->decimal('nilai_diskon', 15, 2);
            $table->decimal('minimal_pembelian', 15, 2)->default(0);
            $table->decimal('maksimal_diskon', 15, 2)->nullable();
            $table->dateTime('mulai_pada');
            $table->dateTime('berakhir_pada');
            $table->boolean('dapat_digabung')->default(false);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('creator_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('promotion_products', function (Blueprint $table) {
            $table->bigIncrements('promotion_product_id');
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamp('created_at')->nullable();

            $table->unique(['promotion_id', 'product_id']);
            $table->foreign('promotion_id')->references('promotion_id')->on('promotions')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('promotion_categories', function (Blueprint $table) {
            $table->bigIncrements('promotion_category_id');
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamp('created_at')->nullable();

            $table->unique(['promotion_id', 'category_id']);
            $table->foreign('promotion_id')->references('promotion_id')->on('promotions')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('category_id')->references('category_id')->on('categories')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_categories');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
    }
};
