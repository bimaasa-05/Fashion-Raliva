<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_grants', function (Blueprint $table) {
            $table->bigIncrements('slot_grant_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedInteger('jumlah_slot');
            $table->string('tipe', 20)->default('gratis');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->string('ref_type', 50)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('created_by')->references('user_id')->on('users')->nullOnDelete();
            $table->index('store_id');
        });

        Schema::create('slot_purchase_requests', function (Blueprint $table) {
            $table->bigIncrements('slot_purchase_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedInteger('jumlah_slot');
            $table->string('alasan')->nullable();
            $table->string('file_bukti')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('alasan_penolakan')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->dateTime('diajukan_pada');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('handled_by')->references('user_id')->on('users')->nullOnDelete();
            $table->index('status');
            $table->index('store_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_purchase_requests');
        Schema::dropIfExists('slot_grants');
    }
};
