<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->string('tipe', 50);
            $table->string('judul', 150);
            $table->text('pesan');
            $table->dateTime('dibaca_pada')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'dibaca_pada']);
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('activity_log_id');
            $table->unsignedBigInteger('user_id');
            $table->string('aksi', 100);
            $table->string('target_tipe', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('nilai_lama')->nullable();
            $table->json('nilai_baru')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['target_tipe', 'target_id']);
            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
    }
};
