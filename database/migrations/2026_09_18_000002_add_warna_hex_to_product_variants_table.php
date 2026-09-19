<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('warna_hex', 9)->nullable()->after('warna');
        });

        foreach (\App\Support\WarnaPalet::all() as $nama => $hex) {
            DB::table('product_variants')
                ->where('warna', $nama)
                ->whereNull('warna_hex')
                ->update(['warna_hex' => $hex]);
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('warna_hex');
        });
    }
};