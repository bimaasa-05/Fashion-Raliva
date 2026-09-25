<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('kekurangan_gudang')->default(0)->after('jumlah_gagal');
            $table->dateTime('qc_perlu_admin_pada')->nullable()->after('kekurangan_gudang');
            $table->string('qc_perlu_admin_catatan', 500)->nullable()->after('qc_perlu_admin_pada');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kekurangan_gudang', 'qc_perlu_admin_pada', 'qc_perlu_admin_catatan']);
        });
    }
};
