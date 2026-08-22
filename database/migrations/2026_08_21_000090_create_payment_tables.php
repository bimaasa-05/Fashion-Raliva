<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('checkout_id')->unique();
            $table->unsignedBigInteger('payment_method_id');
            $table->decimal('jumlah', 15, 2);
            $table->string('status', 30)->default('pending');
            $table->dateTime('batas_waktu');
            $table->dateTime('dibayar_pada')->nullable();
            $table->timestamps();

            $table->foreign('checkout_id')->references('checkout_id')->on('checkouts')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->bigIncrements('payment_proof_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('file_bukti', 255);
            $table->dateTime('uploaded_at');
            $table->timestamps();

            $table->foreign('payment_id')->references('payment_id')->on('payments')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->bigIncrements('payment_verification_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('verifier_id');
            $table->string('status', 20);
            $table->text('alasan')->nullable();
            $table->dateTime('diverifikasi_pada');
            $table->timestamps();

            $table->foreign('payment_id')->references('payment_id')->on('payments')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('verifier_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
        Schema::dropIfExists('payment_proofs');
        Schema::dropIfExists('payments');
    }
};
