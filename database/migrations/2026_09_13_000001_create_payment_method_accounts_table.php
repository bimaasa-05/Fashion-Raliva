<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_method_accounts', function (Blueprint $table) {
            $table->bigIncrements('payment_method_account_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('nama', 100);
            $table->string('kode', 50);
            $table->text('deskripsi')->nullable();
            $table->string('nomor_rekening', 100)->nullable();
            $table->string('nama_pemilik', 150)->nullable();
            $table->string('file_gambar', 255)->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_method_accounts');
    }
};
