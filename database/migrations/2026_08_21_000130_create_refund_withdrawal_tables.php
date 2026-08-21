<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('refund_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->string('tipe_refund', 20);
            $table->text('alasan');
            $table->decimal('jumlah', 15, 2);
            $table->string('status', 30)->default('requested');
            $table->text('alasan_penolakan')->nullable();
            $table->dateTime('diajukan_pada');
            $table->dateTime('selesai_pada')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('payment_id')->references('payment_id')->on('payments')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('requested_by')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
        });

        Schema::create('refund_items', function (Blueprint $table) {
            $table->bigIncrements('refund_item_id');
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedInteger('quantity');
            $table->decimal('nominal', 15, 2);
            $table->text('alasan')->nullable();
            $table->timestamps();

            $table->foreign('refund_id')->references('refund_id')->on('refunds')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->bigIncrements('withdrawal_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->string('status', 30)->default('pending');
            $table->dateTime('diajukan_pada');
            $table->dateTime('ditinjau_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->dateTime('dibayar_pada')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('wallet_id')->references('wallet_id')->on('wallets')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('bank_account_id')->references('bank_account_id')->on('store_bank_accounts')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('refund_items');
        Schema::dropIfExists('refunds');
    }
};
