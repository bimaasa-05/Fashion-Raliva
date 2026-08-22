<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('permission_id');
            $table->string('kode_permission', 100)->unique();
            $table->string('nama_permission', 150);
            $table->text('deskripsi')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->bigIncrements('role_permission_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('role_id')->on('roles')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};
