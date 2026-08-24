<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->bigIncrements('bank_id');
            $table->string('nama_bank', 100);
            $table->string('kode_bank', 20)->unique();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('store_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('bank_account_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('nomor_rekening', 50);
            $table->string('nama_pemilik', 150);
            $table->boolean('is_primary')->default(false);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('bank_id')->references('bank_id')->on('banks')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('payment_method_id');
            $table->string('kode_metode', 50)->unique();
            $table->string('nama_metode', 100);
            $table->unsignedInteger('batas_waktu_menit');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('platform_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('platform_bank_account_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('nomor_rekening', 50);
            $table->string('nama_pemilik', 150);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('bank_id')->references('bank_id')->on('banks')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_bank_accounts');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('store_bank_accounts');
        Schema::dropIfExists('banks');
    }
};
