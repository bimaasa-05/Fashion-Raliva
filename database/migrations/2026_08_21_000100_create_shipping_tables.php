<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->bigIncrements('courier_id');
            $table->string('nama_kurir', 100);
            $table->string('kode_kurir', 50)->unique();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('shipping_services', function (Blueprint $table) {
            $table->bigIncrements('shipping_service_id');
            $table->unsignedBigInteger('courier_id');
            $table->string('nama_layanan', 100);
            $table->string('estimasi_hari', 50)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('courier_id')->references('courier_id')->on('couriers')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('shipment_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('shipping_service_id')->nullable();
            $table->string('nomor_resi', 100)->nullable();
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->date('estimasi_tiba')->nullable();
            $table->dateTime('dikirim_pada')->nullable();
            $table->dateTime('diterima_pada')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();

            $table->index('nomor_resi');
            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('courier_id')->references('courier_id')->on('couriers')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('shipping_service_id')->references('shipping_service_id')->on('shipping_services')->nullOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('shipping_services');
        Schema::dropIfExists('couriers');
    }
};
