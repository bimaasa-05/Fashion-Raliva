<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_topups', function (Blueprint $table) {
            $table->string('file_bukti')->nullable()->after('dibayar_pada');
            $table->string('deskripsi_bukti')->nullable()->after('file_bukti');
            $table->dateTime('bukti_diupload_pada')->nullable()->after('deskripsi_bukti');
        });

        Schema::table('customer_withdrawals', function (Blueprint $table) {
            $table->string('file_bukti')->nullable()->after('catatan_admin');
            $table->string('deskripsi_bukti')->nullable()->after('file_bukti');
            $table->dateTime('bukti_diupload_pada')->nullable()->after('deskripsi_bukti');
        });
    }

    public function down(): void
    {
        Schema::table('customer_topups', function (Blueprint $table) {
            $table->dropColumn(['file_bukti', 'deskripsi_bukti', 'bukti_diupload_pada']);
        });

        Schema::table('customer_withdrawals', function (Blueprint $table) {
            $table->dropColumn(['file_bukti', 'deskripsi_bukti', 'bukti_diupload_pada']);
        });
    }
};