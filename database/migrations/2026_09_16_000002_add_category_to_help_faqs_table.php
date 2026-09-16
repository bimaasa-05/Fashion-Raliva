<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('help_faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('help_category_id')->nullable()->after('jawaban');
            $table->foreign('help_category_id')->references('help_category_id')->on('help_categories')->nullOnDelete();
        });

        $firstCategory = DB::table('help_categories')->orderBy('urutan')->value('help_category_id');
        if ($firstCategory) {
            DB::table('help_faqs')->whereNull('help_category_id')->update(['help_category_id' => $firstCategory]);
        }
    }

    public function down(): void
    {
        Schema::table('help_faqs', function (Blueprint $table) {
            $table->dropForeign(['help_category_id']);
            $table->dropColumn('help_category_id');
        });
    }
};