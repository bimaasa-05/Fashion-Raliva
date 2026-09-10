<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->text('deskripsi')->nullable()->change();
            $table->foreign('category_id')->references('category_id')->on('categories')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->text('deskripsi')->nullable(false)->change();
            $table->foreign('category_id')->references('category_id')->on('categories')->restrictOnDelete()->restrictOnUpdate();
        });
    }
};