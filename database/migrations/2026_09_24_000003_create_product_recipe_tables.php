<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_material_requirements', function (Blueprint $table) {
            $table->bigIncrements('product_material_requirement_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('material_id')->nullable();
            $table->string('nama_bahan', 150);
            $table->string('satuan', 20);
            $table->decimal('jumlah_per_unit', 12, 3);
            $table->decimal('biaya_per_unit', 15, 2);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
            $table->foreign('material_id')->references('bahan_id')->on('bahan_produksi')->nullOnDelete();
            $table->index('product_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('target_produksi')->nullable()->after('harga_dasar');
            $table->decimal('modal_produksi', 15, 2)->nullable()->after('target_produksi');
            $table->decimal('biaya_tambahan', 15, 2)->nullable()->after('modal_produksi');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['target_produksi', 'modal_produksi', 'biaya_tambahan']);
        });
        Schema::dropIfExists('product_material_requirements');
    }
};
