<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slot_purchase_requests', function (Blueprint $table) {
            $table->decimal('harga_per_slot', 15, 2)->default(2000)->after('jumlah_slot');
            $table->decimal('total_harga', 15, 2)->default(0)->after('harga_per_slot');
            $table->string('payment_status', 30)->default('menunggu_verifikasi')->after('total_harga');
            $table->string('metode_pembayaran', 50)->nullable()->after('payment_status');
            $table->decimal('biaya_layanan', 15, 2)->default(0)->after('metode_pembayaran');
            $table->dateTime('paid_at')->nullable()->after('biaya_layanan');
        });
    }

    public function down(): void
    {
        Schema::table('slot_purchase_requests', function (Blueprint $table) {
            $table->dropColumn(['harga_per_slot', 'total_harga', 'payment_status', 'metode_pembayaran', 'biaya_layanan', 'paid_at']);
        });
    }
};