<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('wallet_transaction_id');
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('commission_id')->nullable();
            $table->unsignedBigInteger('refund_id')->nullable();
            $table->unsignedBigInteger('withdrawal_id')->nullable();
            $table->string('jenis_transaksi', 30);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('wallet_id')->on('wallets')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('order_id')->references('order_id')->on('orders')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('commission_id')->references('commission_id')->on('commissions')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('refund_id')->references('refund_id')->on('refunds')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('withdrawal_id')->references('withdrawal_id')->on('withdrawals')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
