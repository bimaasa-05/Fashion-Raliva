<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('complaint_id')->nullable()->after('order_id');
            $table->index('complaint_id');
            $table->foreign('complaint_id')
                ->references('complaint_id')
                ->on('complaints')
                ->nullOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign(['complaint_id']);
            $table->dropIndex(['complaint_id']);
            $table->dropColumn('complaint_id');
        });
    }
};