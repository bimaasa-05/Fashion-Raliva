<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_slot_packages', function (Blueprint $table) {
            $table->bigIncrements('slot_package_id');
            $table->string('nama_paket', 100);
            $table->decimal('harga', 15, 2);
            $table->unsignedInteger('jumlah_slot');
            $table->unsignedInteger('durasi_hari');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('store_slot_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('slot_subscription_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('slot_package_id');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_berakhir');
            $table->unsignedInteger('jumlah_slot');
            $table->unsignedInteger('slot_terpakai')->default(0);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('slot_package_id')->references('slot_package_id')->on('product_slot_packages')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_slot_subscriptions');
        Schema::dropIfExists('product_slot_packages');
    }
};
