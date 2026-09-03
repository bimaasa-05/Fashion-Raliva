<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->unsignedBigInteger('user_id');
            $table->string('label', 100);
            $table->string('nama_penerima', 150);
            $table->string('nomor_telepon', 30);
            $table->string('alamat', 500);
            $table->string('kota', 100);
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 20)->nullable();
            $table->string('negara', 100)->default('Indonesia');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};