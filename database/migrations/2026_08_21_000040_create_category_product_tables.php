<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('parent_id')->references('category_id')->on('categories')->nullOnDelete()->restrictOnUpdate();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('category_id');
            $table->string('nama_produk', 200);
            $table->text('deskripsi');
            $table->decimal('harga_dasar', 15, 2);
            $table->string('tipe_produk', 20)->default('regular');
            $table->string('status', 30)->default('draft');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('category_id')->references('category_id')->on('categories')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('product_variant_id');
            $table->unsignedBigInteger('product_id');
            $table->string('sku', 100)->unique();
            $table->string('warna', 100)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->decimal('harga', 15, 2);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('product_image_id');
            $table->unsignedBigInteger('product_id');
            $table->string('file_gambar', 255);
            $table->unsignedInteger('urutan')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
