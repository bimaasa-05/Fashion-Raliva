<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('kategori', 100)->default('Lainnya')->after('jenis_transaksi');
        });

        Schema::create('store_courier_settings', function (Blueprint $table) {
            $table->id('store_courier_setting_id');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->unsignedBigInteger('courier_id');
            $table->foreign('courier_id')->references('courier_id')->on('couriers')->cascadeOnDelete();
            $table->unsignedBigInteger('shipping_service_id')->nullable();
            $table->foreign('shipping_service_id')->references('shipping_service_id')->on('shipping_services')->nullOnDelete();
            $table->boolean('is_aktif')->default(true);
            $table->decimal('ongkir_override', 15, 2)->nullable();
            $table->integer('estimasi_override')->nullable();
            $table->timestamps();
            $table->unique(['store_id', 'courier_id', 'shipping_service_id'], 'scs_toko_kurir_layanan_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_courier_settings');
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
