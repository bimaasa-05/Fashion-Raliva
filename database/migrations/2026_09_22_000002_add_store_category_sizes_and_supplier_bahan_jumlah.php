<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_category_sizes', function (Blueprint $table) {
            $table->id('store_category_size_id');
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->references('store_category_id')->on('store_categories')->cascadeOnDelete();
            $table->string('ukuran_label', 100);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->unique(['store_category_id', 'ukuran_label']);
        });

        Schema::table('supplier_bahan', function (Blueprint $table) {
            $table->integer('jumlah')->default(0)->after('satuan');
        });
    }

    public function down(): void
    {
        Schema::table('supplier_bahan', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
        Schema::dropIfExists('store_category_sizes');
    }
};
