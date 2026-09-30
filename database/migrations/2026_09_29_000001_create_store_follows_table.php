<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_follows', function (Blueprint $table) {
            $table->bigIncrements('store_follow_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->unique(['user_id', 'store_id']);
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_follows');
    }
};
