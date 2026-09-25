<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_update_requests', function (Blueprint $table) {
            $table->string('logo', 255)->nullable()->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('store_update_requests', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
