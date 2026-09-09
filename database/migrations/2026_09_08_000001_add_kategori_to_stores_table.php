<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('kategori', 100)->nullable()->after('nama_toko');
        });

        DB::table('stores')->whereNull('kategori')->update(['kategori' => 'Fashion & Lifestyle']);
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};