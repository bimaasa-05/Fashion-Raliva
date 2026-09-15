<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_operasional', function (Blueprint $table) {
            $table->bigIncrements('permintaan_id');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('stores')->cascadeOnDelete();
            $table->unsignedBigInteger('pemohon_id'); // user yang mengajukan
            $table->foreign('pemohon_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->enum('jenis_permintaan', ['stok', 'produksi', 'gudang', 'pengiriman', 'supplier', 'lainnya']);
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->json('payload')->nullable(); // detail tambahan (bahan, jumlah, dll)
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->unsignedBigInteger('admin_id')->nullable(); // approver
            $table->foreign('admin_id')->references('user_id')->on('users')->nullOnDelete();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_operasional');
    }
};
