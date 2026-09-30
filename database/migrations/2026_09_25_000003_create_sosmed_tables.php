<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sosmed_platforms', function (Blueprint $table) {
            $table->id('sosmed_platform_id');
            $table->string('nama_platform', 50)->unique();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('store_socials', function (Blueprint $table) {
            $table->id('store_social_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('sosmed_platform_id')->nullable();
            $table->string('nama_custom', 50)->nullable();
            $table->string('url', 255);
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->foreign('sosmed_platform_id')->references('sosmed_platform_id')->on('sosmed_platforms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_socials');
        Schema::dropIfExists('sosmed_platforms');
    }
};
