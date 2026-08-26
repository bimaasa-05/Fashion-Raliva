<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('alasan_penolakan');
        });
    }
};
