<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_faqs', function (Blueprint $table) {
            $table->bigIncrements('landing_faq_id');
            $table->string('pertanyaan_id', 255);
            $table->text('jawaban_id');
            $table->string('pertanyaan_en', 255);
            $table->text('jawaban_en');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'urutan']);
        });

        $now = now();

        $defaults = [
            [
                'pertanyaan_id' => 'Apakah ada biaya pendaftaran?',
                'jawaban_id' => 'Tidak ada. Pembuatan akun Owner gratis.',
                'pertanyaan_en' => 'Is there a registration fee?',
                'jawaban_en' => 'No. Creating an Owner account is free.',
                'urutan' => 1,
            ],
            [
                'pertanyaan_id' => 'Apakah ada komisi platform?',
                'jawaban_id' => 'Ya. Komisi platform sebesar {percent}% dari dasar penjualan dan dipotong otomatis saat pesanan selesai.',
                'pertanyaan_en' => 'Is there a platform commission?',
                'jawaban_en' => 'Yes. The platform commission is {percent}% of the sales base and is deducted automatically when an order is completed.',
                'urutan' => 2,
            ],
            [
                'pertanyaan_id' => 'Berapa lama proses verifikasi?',
                'jawaban_id' => 'Setelah dokumen lengkap, tim kami akan memeriksa dan mengirim notifikasi. Waktunya tergantung kelengkapan dokumen.',
                'pertanyaan_en' => 'How long does verification take?',
                'jawaban_en' => 'Once your documents are complete, our team will review them and send a notification. Timing depends on document completeness.',
                'urutan' => 3,
            ],
            [
                'pertanyaan_id' => 'Dokumen apa saja yang diperlukan?',
                'jawaban_id' => 'KTP wajib. NPWP, foto depan toko, dan SIU bersifat opsional.',
                'pertanyaan_en' => 'What documents are required?',
                'jawaban_en' => 'KTP is required. NPWP, storefront photo, and SIU are optional.',
                'urutan' => 4,
            ],
            [
                'pertanyaan_id' => 'Bagaimana cara pencairan dana?',
                'jawaban_id' => 'Pesanan yang selesai masuk ke saldo toko setelah komisi platform. Anda dapat mengajukan pencairan dari menu Keuangan. Tidak ada potongan tambahan saat pencairan Owner diproses.',
                'pertanyaan_en' => 'How do withdrawals work?',
                'jawaban_en' => 'Completed orders are credited to your store balance after platform commission. You can request a withdrawal from the Finance menu. There is no additional deduction when the Owner withdrawal is processed.',
                'urutan' => 5,
            ],
        ];

        $rows = array_map(static function (array $row) use ($now): array {
            return $row + [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $defaults);

        DB::table('landing_faqs')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_faqs');
    }
};
