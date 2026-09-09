<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaint_messages', function (Blueprint $table) {
            $table->softDeletes();
            $table->json('deleted_by')->nullable()->after('lampiran');
            $table->timestamp('edited_at')->nullable()->after('deleted_by');
        });
    }

    public function down(): void
    {
        Schema::table('complaint_messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'deleted_by', 'edited_at']);
        });
    }
};