<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_wallets', function (Blueprint $table) {
            $table->bigIncrements('customer_wallet_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('saldo_tersedia', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('customer_topups', function (Blueprint $table) {
            $table->bigIncrements('customer_topup_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->decimal('jumlah', 15, 2);
            $table->string('status', 30)->default('pending');
            $table->dateTime('batas_waktu')->nullable();
            $table->dateTime('dibayar_pada')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('payment_id')->references('payment_id')->on('payments')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('customer_wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('customer_wallet_transaction_id');
            $table->unsignedBigInteger('customer_wallet_id');
            $table->unsignedBigInteger('customer_topup_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('jenis_transaksi', 30);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('customer_wallet_id')->references('customer_wallet_id')->on('customer_wallets')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('customer_topup_id')->references('customer_topup_id')->on('customer_topups')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_wallet_transactions');
        Schema::dropIfExists('customer_topups');
        Schema::dropIfExists('customer_wallets');
    }
};