<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('store_id');
            $table->unsignedBigInteger('owner_id');
            $table->string('nama_toko', 150);
            $table->string('logo', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            $table->string('nomor_telepon', 30)->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();

            $table->foreign('owner_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::create('store_staff', function (Blueprint $table) {
            $table->bigIncrements('store_staff_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_penugasan')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->unique(['store_id', 'user_id']);
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('store_staff_permissions', function (Blueprint $table) {
            $table->bigIncrements('store_staff_permission_id');
            $table->unsignedBigInteger('store_staff_id');
            $table->unsignedBigInteger('permission_id');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->unique(['store_staff_id', 'permission_id']);
            $table->foreign('store_staff_id')->references('store_staff_id')->on('store_staff')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_staff_permissions');
        Schema::dropIfExists('store_staff');
        Schema::dropIfExists('stores');
    }
};
