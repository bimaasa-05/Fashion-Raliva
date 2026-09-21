<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_order_bahan', function (Blueprint $table) {
            $table->string('sumber', 20)->default('admin')->after('catatan');
            $table->string('dibuat_oleh_role', 30)->nullable()->after('sumber');
            $table->index(['order_id', 'sumber']);
        });

        // Backfill existing rows from creator role (fallback admin)
        DB::table('production_order_bahan as pob')
            ->leftJoin('users as u', 'u.user_id', '=', 'pob.created_by')
            ->leftJoin('roles as r', 'r.role_id', '=', 'u.role_id')
            ->whereNull('pob.dibuat_oleh_role')
            ->update([
                'pob.dibuat_oleh_role' => DB::raw('COALESCE(r.nama_role, \'Admin\')'),
                'pob.sumber' => DB::raw("CASE WHEN r.nama_role = 'Produksi' THEN 'produksi' ELSE 'admin' END"),
            ]);
    }

    public function down(): void
    {
        Schema::table('production_order_bahan', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'sumber']);
            $table->dropColumn(['sumber', 'dibuat_oleh_role']);
        });
    }
};
