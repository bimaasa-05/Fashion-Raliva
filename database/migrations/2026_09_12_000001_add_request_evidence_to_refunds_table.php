<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->string('file_bukti_request')->nullable()->after('bukti_diupload_pada');
            $table->string('deskripsi_bukti_request')->nullable()->after('file_bukti_request');
            $table->dateTime('bukti_request_diupload_pada')->nullable()->after('deskripsi_bukti_request');
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn(['file_bukti_request', 'deskripsi_bukti_request', 'bukti_request_diupload_pada']);
        });
    }
};
