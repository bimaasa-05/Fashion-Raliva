<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_package_promotions', function (Blueprint $table) {
            $table->bigIncrements('slot_promo_id');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('slot_package_id');
            $table->string('kode_promo', 100)->unique();
            $table->string('nama_promo', 150);
            $table->string('tipe_diskon', 20);
            $table->decimal('nilai_diskon', 15, 2);
            $table->decimal('maksimal_diskon', 15, 2)->nullable();
            $table->dateTime('mulai_pada');
            $table->dateTime('berakhir_pada');
            $table->string('deskripsi', 1000)->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->foreign('creator_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('slot_package_id')->references('slot_package_id')->on('product_slot_packages')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_package_promotions');
    }
};