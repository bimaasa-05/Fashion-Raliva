<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_documents', function (Blueprint $table) {
            $table->id('store_document_id');
            $table->unsignedBigInteger('store_id');
            $table->string('jenis'); // ktp, npwp, foto_depan, siu
            $table->string('path');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_documents');
    }
};
