<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_operational_costs', function (Blueprint $table) {
            $table->bigIncrements('product_operational_cost_id');
            $table->unsignedBigInteger('product_id');
            $table->string('nama_biaya', 100);
            $table->decimal('nominal', 15, 2);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
            $table->index('product_id');
        });

        DB::table('products')
            ->where('biaya_tambahan', '>', 0)
            ->orderBy('product_id')
            ->chunkById(200, function ($products) {
                $now = now();
                $rows = [];
                foreach ($products as $product) {
                    $rows[] = [
                        'product_id' => $product->product_id,
                        'nama_biaya' => 'Biaya operasional',
                        'nominal' => $product->biaya_tambahan,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('product_operational_costs')->insert($rows);
            }, 'product_id');
    }

    public function down(): void
    {
        DB::table('products')->orderBy('product_id')->chunkById(200, function ($products) {
            foreach ($products as $product) {
                $total = (float) DB::table('product_operational_costs')
                    ->where('product_id', $product->product_id)
                    ->sum('nominal');
                DB::table('products')->where('product_id', $product->product_id)->update(['biaya_tambahan' => $total]);
            }
        }, 'product_id');
        Schema::dropIfExists('product_operational_costs');
    }
};
