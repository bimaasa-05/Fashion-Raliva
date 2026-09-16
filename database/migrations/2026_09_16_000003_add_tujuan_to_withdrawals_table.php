<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('tipe_tujuan', 20)->nullable()->after('bank_account_id');
            $table->unsignedBigInteger('bank_id')->nullable()->after('tipe_tujuan');
            $table->string('penyedia', 100)->nullable()->after('bank_id');
            $table->string('nomor_tujuan', 50)->nullable()->after('penyedia');

            $table->foreign('bank_id')->references('bank_id')->on('banks')->nullOnDelete();
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_account_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn(['tipe_tujuan', 'bank_id', 'penyedia', 'nomor_tujuan']);
        });
    }
};