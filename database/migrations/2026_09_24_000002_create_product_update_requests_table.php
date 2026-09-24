<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_update_requests');
        Schema::create('product_update_requests', function (Blueprint $table) {
            $table->id('product_update_request_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->string('status', 20)->default('pending');
            $table->json('before_snapshot')->nullable();
            $table->json('after_payload');
            $table->json('remove_image_ids')->nullable();
            $table->json('staged_images')->nullable();
            $table->text('review_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->foreign('requested_by')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete();
            $table->index(['product_id', 'status']);
            $table->index(['store_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_update_requests');
    }
};
