<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['checkout_id']);
            $table->dropUnique(['checkout_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('checkout_id')->nullable()->change();
            $table->unsignedBigInteger('topup_id')->nullable()->unique()->after('checkout_id');
            $table->foreign('topup_id')->references('customer_topup_id')->on('customer_topups')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('checkout_id')->references('checkout_id')->on('checkouts')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['topup_id']);
            $table->dropUnique(['topup_id']);
            $table->dropColumn('topup_id');
            $table->dropForeign(['checkout_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('checkout_id')->nullable(false)->change();
            $table->unique('checkout_id');
            $table->foreign('checkout_id')->references('checkout_id')->on('checkouts')->restrictOnDelete()->restrictOnUpdate();
        });
    }
};