<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_categories', function (Blueprint $table) {
            $table->bigIncrements('help_category_id');
            $table->string('icon', 50);
            $table->string('judul', 100);
            $table->string('subjudul', 150)->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('help_faqs', function (Blueprint $table) {
            $table->bigIncrements('help_faq_id');
            $table->string('pertanyaan', 255);
            $table->text('jawaban');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_faqs');
        Schema::dropIfExists('help_categories');
    }
};
