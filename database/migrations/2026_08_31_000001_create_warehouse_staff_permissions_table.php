<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_staff_permissions', function (Blueprint $table) {
            $table->bigIncrements('warehouse_staff_permission_id');
            $table->unsignedBigInteger('warehouse_staff_id');
            $table->unsignedBigInteger('permission_id');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();

            $table->unique(['warehouse_staff_id', 'permission_id'], 'wsp_staff_perm_unique');
            $table->foreign('warehouse_staff_id')->references('warehouse_staff_id')->on('warehouse_staff')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_staff_permissions');
    }
};