<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tipe_order', 20)->nullable()->after('status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->text('catatan_custom')->nullable()->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tipe_order');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('catatan_custom');
        });
    }
};