<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Penanda persetujuan Owner atas produk (forward ke SuperAdmin).
     * Produk tetap pending sampai SuperAdmin menyetujui.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('owner_verified_at')->nullable()->after('alasan_penolakan');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('owner_verified_at');
        });
    }
};
