<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tipe_pesanan', 10)->default('online')->after('status');
            $table->datetime('diambil_pada')->nullable()->after('tanggal_packing');
        });

        $offlineIds = DB::table('orders as o')
            ->leftJoin('checkouts as c', 'o.checkout_id', '=', 'c.checkout_id')
            ->leftJoin('users as u', 'c.user_id', '=', 'u.user_id')
            ->where(function ($q) {
                $q->whereNotNull('c.nama_penerima')
                    ->orWhere('u.email', 'like', '%@offline.raliva.test')
                    ->orWhereExists(function ($exists) {
                        $exists->select(DB::raw(1))
                            ->from('payments')
                            ->whereColumn('payments.checkout_id', 'c.checkout_id')
                            ->whereNull('payments.payment_account_id');
                    });
            })
            ->distinct()
            ->pluck('o.order_id');

        if ($offlineIds->isNotEmpty()) {
            DB::table('orders')->whereIn('order_id', $offlineIds)->update(['tipe_pesanan' => 'offline']);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tipe_pesanan', 'diambil_pada']);
        });
    }
};