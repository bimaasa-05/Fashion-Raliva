<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_documents', function (Blueprint $table) {
            $table->unique(['store_id', 'jenis'], 'store_documents_store_jenis_unique');
        });
    }

    public function down(): void
    {
        Schema::table('store_documents', function (Blueprint $table) {
            $table->dropUnique('store_documents_store_jenis_unique');
        });
    }
};
