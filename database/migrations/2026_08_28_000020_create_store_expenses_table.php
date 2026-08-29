<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_expenses', function (Blueprint $table) {
            $table->id('store_expense_id');
            $table->unsignedBigInteger('store_id');
            $table->string('nama');
            $table->string('kategori')->default('Lainnya');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_expenses');
    }
};
