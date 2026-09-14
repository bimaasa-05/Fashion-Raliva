<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_method_account_id')->nullable()->after('payment_method_id');
            $table->foreign('payment_method_account_id')->references('payment_method_account_id')->on('payment_method_accounts')->nullOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_method_account_id']);
            $table->dropColumn('payment_method_account_id');
        });
    }
};
