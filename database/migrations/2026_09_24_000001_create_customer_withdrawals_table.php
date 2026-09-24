<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_withdrawals', function (Blueprint $table) {
            $table->bigIncrements('customer_withdrawal_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('jumlah_bersih', 15, 2);
            $table->string('tipe_tujuan', 20);
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('penyedia', 100)->nullable();
            $table->string('nomor_tujuan', 50);
            $table->string('nama_pemilik', 150)->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('diajukan_pada')->nullable();
            $table->timestamp('diproses_pada')->nullable();
            $table->string('catatan_admin', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::table('customer_wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_withdrawal_id')->nullable()->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('customer_wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('customer_withdrawal_id');
        });

        Schema::dropIfExists('customer_withdrawals');
    }
};
