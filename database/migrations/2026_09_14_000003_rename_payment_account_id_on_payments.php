<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lanjutan migrasi 000002: rename payments.payment_method_account_id
     * menjadi payment_account_id + FK ke platform_bank_accounts.
     */
    public function up(): void
    {
        if (Schema::hasColumn('payments', 'payment_method_account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payment_method_account_id', 'payment_account_id');
            });
        }

        if (Schema::hasColumn('payments', 'payment_account_id')) {
            $foreignKeys = array_column(Schema::getForeignKeys('payments'), 'name');
            if (! in_array('payments_payment_account_id_foreign', $foreignKeys, true)) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->foreign('payment_account_id')
                        ->references('platform_bank_account_id')
                        ->on('platform_bank_accounts')
                        ->nullOnDelete()
                        ->restrictOnUpdate();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'payment_account_id')) {
            $foreignKeys = array_column(Schema::getForeignKeys('payments'), 'name');
            if (in_array('payments_payment_account_id_foreign', $foreignKeys, true)) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropForeign(['payment_account_id']);
                });
            }
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payment_account_id', 'payment_method_account_id');
            });
        }
    }
};
