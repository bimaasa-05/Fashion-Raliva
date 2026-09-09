<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('stok_minimum');
            $table->index('supplier_id');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
        });

        DB::statement("UPDATE warehouse_stocks ws
            SET ws.supplier_id = (
                SELECT s.supplier_id
                FROM stock_movements sm
                JOIN suppliers s ON s.supplier_id = sm.sumber_id
                WHERE sm.sumber_tipe = 'supplier'
                  AND sm.warehouse_id = ws.warehouse_id
                  AND sm.product_variant_id = ws.product_variant_id
                ORDER BY sm.created_at DESC
                LIMIT 1
            )
            WHERE ws.supplier_id IS NULL");
    }

    public function down(): void
    {
        Schema::table('warehouse_stocks', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};