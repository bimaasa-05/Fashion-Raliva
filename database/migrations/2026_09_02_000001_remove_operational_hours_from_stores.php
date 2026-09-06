<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('stores', 'operational_hours')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('operational_hours');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('stores', 'operational_hours')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->json('operational_hours')->nullable()->after('nomor_telepon');
            });
        }
    }
};
