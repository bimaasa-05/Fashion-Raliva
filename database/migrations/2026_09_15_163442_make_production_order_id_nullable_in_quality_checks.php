<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('production_order_id')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('production_order_id')->nullable(false)->change();
        });
    }
};
