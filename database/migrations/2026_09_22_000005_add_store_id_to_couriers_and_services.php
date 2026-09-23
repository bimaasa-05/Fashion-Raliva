<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('courier_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->nullOnDelete();
            $table->index('store_id');
        });

        Schema::table('shipping_services', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('shipping_service_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->nullOnDelete();
            $table->index('store_id');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropIndex(['store_id']);
            $table->dropColumn('store_id');
        });
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropIndex(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
