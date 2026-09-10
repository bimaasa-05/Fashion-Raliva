<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('platform_bank_account_id')->nullable()->after('store_id');
            $table->string('payment_status', 30)->default('menunggu_verifikasi')->after('nominal_bid');
            $table->string('metode_pembayaran', 50)->nullable()->after('payment_status');
            $table->string('file_bukti')->nullable()->after('metode_pembayaran');
            $table->dateTime('paid_at')->nullable()->after('file_bukti');
            $table->unsignedBigInteger('handled_by')->nullable()->after('paid_at');
            $table->text('alasan_penolakan')->nullable()->after('handled_by');

            $table->foreign('platform_bank_account_id')->references('platform_bank_account_id')->on('platform_bank_accounts')->nullOnDelete();
            $table->foreign('handled_by')->references('user_id')->on('users')->nullOnDelete();
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_slot_id')->nullable()->after('withdrawal_id');
            $table->foreign('ad_slot_id')->references('ad_slot_id')->on('ad_slots')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['ad_slot_id']);
            $table->dropColumn('ad_slot_id');
        });

        Schema::table('ad_slots', function (Blueprint $table) {
            $table->dropForeign(['platform_bank_account_id']);
            $table->dropForeign(['handled_by']);
            $table->dropColumn(['platform_bank_account_id', 'payment_status', 'metode_pembayaran', 'file_bukti', 'paid_at', 'handled_by', 'alasan_penolakan']);
        });
    }
};