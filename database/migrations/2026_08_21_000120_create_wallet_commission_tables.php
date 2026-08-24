<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('wallet_id');
            $table->unsignedBigInteger('store_id')->unique();
            $table->decimal('saldo_tertahan', 15, 2)->default(0);
            $table->decimal('saldo_tersedia', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('commissions', function (Blueprint $table) {
            $table->bigIncrements('commission_id');
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('store_id');
            $table->decimal('persentase', 5, 2);
            $table->decimal('dasar_perhitungan', 15, 2);
            $table->decimal('jumlah_komisi', 15, 2);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('wallets');
    }
};
