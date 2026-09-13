<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) checkouts.user_id -> nullable + snapshot alamat tamu
        Schema::table('checkouts', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
            }
        });
        DB::statement('ALTER TABLE `checkouts` MODIFY `user_id` BIGINT UNSIGNED NULL');

        Schema::table('checkouts', function (Blueprint $table) {
            if (! Schema::hasColumn('checkouts', 'email_pelanggan')) {
                $table->string('email_pelanggan', 150)->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('checkouts', 'nama_penerima')) {
                $table->string('nama_penerima', 150)->nullable()->after('email_pelanggan');
            }
            if (! Schema::hasColumn('checkouts', 'nomor_telepon')) {
                $table->string('nomor_telepon', 30)->nullable()->after('nama_penerima');
            }
            if (! Schema::hasColumn('checkouts', 'alamat')) {
                $table->text('alamat')->nullable()->after('nomor_telepon');
            }
            if (! Schema::hasColumn('checkouts', 'kota')) {
                $table->string('kota', 100)->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('checkouts', 'provinsi')) {
                $table->string('provinsi', 100)->nullable()->after('kota');
            }
            if (! Schema::hasColumn('checkouts', 'kode_pos')) {
                $table->string('kode_pos', 20)->nullable()->after('provinsi');
            }
        });

        Schema::table('checkouts', function (Blueprint $table) {
            try {
                $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            } catch (\Throwable $e) {
            }
        });

        // 2) orders.catatan (terpisah dari catatan_gudang)
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'catatan')) {
                $table->text('catatan')->nullable()->after('catatan_gudang');
            }
        });

        // 3) payments.payment_method_id -> nullable (dipilih di langkah Bayar)
        Schema::table('payments', function (Blueprint $table) {
            try {
                $table->dropForeign(['payment_method_id']);
            } catch (\Throwable $e) {
            }
        });
        DB::statement('ALTER TABLE `payments` MODIFY `payment_method_id` BIGINT UNSIGNED NULL');

        Schema::table('payments', function (Blueprint $table) {
            try {
                $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->restrictOnDelete()->restrictOnUpdate();
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            try {
                $table->dropForeign(['payment_method_id']);
            } catch (\Throwable $e) {
            }
        });
        // kembalikan ke NOT NULL hanya bila tidak ada NULL (migrate:fresh akan drop anyway)
        DB::statement('UPDATE `payments` SET `payment_method_id` = (SELECT MIN(payment_method_id) FROM payment_methods) WHERE `payment_method_id` IS NULL');
        DB::statement('ALTER TABLE `payments` MODIFY `payment_method_id` BIGINT UNSIGNED NOT NULL');
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->restrictOnDelete()->restrictOnUpdate();
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });

        Schema::table('checkouts', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
            }
        });
        // hapus snapshot dulu sebelum ubah user_id
        Schema::table('checkouts', function (Blueprint $table) {
            $cols = ['email_pelanggan','nama_penerima','nomor_telepon','alamat','kota','provinsi','kode_pos'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('checkouts', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
        DB::statement('UPDATE `checkouts` SET `user_id` = (SELECT MIN(user_id) FROM users) WHERE `user_id` IS NULL');
        DB::statement('ALTER TABLE `checkouts` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
        Schema::table('checkouts', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
    }
};
