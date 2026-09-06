<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('file_bukti')->nullable()->after('alasan_penolakan');
            $table->string('deskripsi_bukti')->nullable()->after('file_bukti');
            $table->dateTime('bukti_diupload_pada')->nullable()->after('deskripsi_bukti');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['file_bukti', 'deskripsi_bukti', 'bukti_diupload_pada']);
        });
    }
};
