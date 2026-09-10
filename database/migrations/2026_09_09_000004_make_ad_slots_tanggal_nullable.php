<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_slots', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->change();
            $table->date('tanggal_selesai')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ad_slots', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable(false)->change();
            $table->date('tanggal_selesai')->nullable(false)->change();
        });
    }
};