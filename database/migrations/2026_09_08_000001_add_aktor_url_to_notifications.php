<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'aktor_id')) {
                $table->unsignedBigInteger('aktor_id')->nullable()->after('user_id');
                $table->foreign('aktor_id')->references('user_id')->on('users')->nullOnDelete()->restrictOnUpdate();
            }

            if (! Schema::hasColumn('notifications', 'url')) {
                $table->string('url', 500)->nullable()->after('pesan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'aktor_id')) {
                $table->dropForeign(['aktor_id']);
                $table->dropColumn('aktor_id');
            }

            if (Schema::hasColumn('notifications', 'url')) {
                $table->dropColumn('url');
            }
        });
    }
};