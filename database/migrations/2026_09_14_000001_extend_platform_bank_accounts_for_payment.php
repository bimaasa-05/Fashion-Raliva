<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
        });

        Schema::table('platform_bank_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable()->change();
            $table->string('jenis', 20)->default('bank_transfer')->after('bank_id');
            $table->string('nama', 100)->nullable()->after('jenis');
            $table->string('kode', 50)->nullable()->after('nama');
            $table->text('deskripsi')->nullable()->after('kode');
            $table->string('nomor_rekening', 50)->nullable()->change();
            $table->string('nama_pemilik', 150)->nullable()->change();
            $table->string('file_gambar', 255)->nullable()->after('nama_pemilik');
            $table->unsignedInteger('urutan')->default(0)->after('file_gambar');

            $table->foreign('bank_id')->references('bank_id')->on('banks')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('platform_bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
        });

        Schema::table('platform_bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'nama', 'kode', 'deskripsi', 'file_gambar', 'urutan']);
            $table->unsignedBigInteger('bank_id')->nullable(false)->change();

            $table->foreign('bank_id')->references('bank_id')->on('banks')->restrictOnDelete()->restrictOnUpdate();
        });
    }
};
