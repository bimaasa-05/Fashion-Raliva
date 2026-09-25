<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->decimal('tarif', 15, 2)->default(0)->after('estimasi_hari');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->dropColumn('tarif');
        });
    }
};
