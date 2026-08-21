<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_item_id')->unique();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedTinyInteger('rating');
            $table->text('ulasan')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('product_id')->references('product_id')->on('products')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('complaint_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->string('kategori', 30);
            $table->string('subjek', 150);
            $table->text('deskripsi');
            $table->string('status', 30)->default('open');
            $table->dateTime('dibuat_pada');
            $table->dateTime('diselesaikan_pada')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('complaint_messages', function (Blueprint $table) {
            $table->bigIncrements('complaint_message_id');
            $table->unsignedBigInteger('complaint_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('pesan');
            $table->string('lampiran', 255)->nullable();
            $table->timestamps();

            $table->foreign('complaint_id')->references('complaint_id')->on('complaints')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('sender_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_messages');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('reviews');
    }
};
