<?php

use App\Support\WarnaPalet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (WarnaPalet::all() as $name => $hex) {
            DB::table('product_variants')
                ->where(fn ($query) => $query->whereNull('warna_hex')->orWhere('warna_hex', ''))
                ->whereRaw('LOWER(TRIM(warna)) = ?', [mb_strtolower($name)])
                ->update(['warna_hex' => $hex]);
        }
    }

    public function down(): void
    {
        //
    }
};
