<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id('city_id');
            $table->string('nama_kota', 100)->unique();
            $table->string('pulau', 50);
            $table->timestamps();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->string('kota', 100)->nullable()->after('alamat');
        });

        Schema::table('store_update_requests', function (Blueprint $table) {
            $table->string('kota', 100)->nullable()->after('alamat');
        });

        Schema::table('shipping_services', function (Blueprint $table) {
            $table->decimal('tarif_sekota', 15, 2)->default(0)->after('tarif');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->dropColumn('tarif_sekota');
        });
        Schema::table('store_update_requests', function (Blueprint $table) {
            $table->dropColumn('kota');
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('kota');
        });
        Schema::dropIfExists('cities');
    }
};
