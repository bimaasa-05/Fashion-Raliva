<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pindahkan 12 akun payment_method_accounts ke platform_bank_accounts
     * (jenis = kode_metode; bank_transfer di-link ke banks via kode),
     * remap payments, rename kolom FK, lalu drop tabel lama.
     */
    public function up(): void
    {
        if (Schema::hasTable('payment_method_accounts')) {
            $now = now();
            $methodIds = DB::table('payment_methods')->pluck('payment_method_id', 'kode_metode');
            $bankIds = DB::table('banks')->pluck('bank_id', 'kode_bank');

            $methodIdToJenis = [];
            foreach (['qris' => 'qris', 'ewallet' => 'ewallet', 'bank_transfer' => 'bank_transfer'] as $kode => $jenis) {
                if (isset($methodIds[$kode])) {
                    $methodIdToJenis[(int) $methodIds[$kode]] = $jenis;
                }
            }

            $idMap = [];
            $rows = DB::table('payment_method_accounts')->orderBy('payment_method_account_id')->get();

            foreach ($rows as $row) {
                $jenis = $methodIdToJenis[(int) $row->payment_method_id] ?? 'bank_transfer';
                $bankId = null;
                if ($jenis === 'bank_transfer') {
                    $bankId = $bankIds[strtolower((string) $row->kode)] ?? null;
                }

                $payload = [
                    'jenis' => $jenis,
                    'bank_id' => $bankId,
                    'nama' => $row->nama,
                    'kode' => $row->kode,
                    'deskripsi' => $row->deskripsi,
                    'nomor_rekening' => $row->nomor_rekening,
                    'nama_pemilik' => $row->nama_pemilik,
                    'file_gambar' => $row->file_gambar,
                    'urutan' => $row->urutan,
                    'status' => $row->status,
                    'updated_at' => $now,
                ];

                if ($jenis === 'bank_transfer' && $bankId) {
                    $existing = DB::table('platform_bank_accounts')->where('bank_id', $bankId)->first();
                    if ($existing) {
                        DB::table('platform_bank_accounts')
                            ->where('platform_bank_account_id', $existing->platform_bank_account_id)
                            ->update($payload);
                        $idMap[(int) $row->payment_method_account_id] = (int) $existing->platform_bank_account_id;

                        continue;
                    }
                }

                $payload['created_at'] = $row->created_at ?? $now;
                $idMap[(int) $row->payment_method_account_id] = (int) DB::table('platform_bank_accounts')->insertGetId($payload);
            }

            foreach ($idMap as $oldId => $newId) {
                DB::table('payments')
                    ->where('payment_method_account_id', $oldId)
                    ->update(['payment_method_account_id' => $newId]);
            }

            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['payment_method_account_id']);
            });

            Schema::dropIfExists('payment_method_accounts');
        }

        if (Schema::hasColumn('payments', 'payment_account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('payment_account_id')
                    ->references('platform_bank_account_id')
                    ->on('platform_bank_accounts')
                    ->nullOnDelete()
                    ->restrictOnUpdate();
            });
        }
    }

    public function down(): void
    {
        // Rollback skema saja (data hasil merge tidak dikembalikan).
        if (! Schema::hasTable('payment_method_accounts')) {
            Schema::create('payment_method_accounts', function (Blueprint $table) {
                $table->bigIncrements('payment_method_account_id');
                $table->unsignedBigInteger('payment_method_id');
                $table->string('nama', 100);
                $table->string('kode', 50);
                $table->text('deskripsi')->nullable();
                $table->string('nomor_rekening', 100)->nullable();
                $table->string('nama_pemilik', 150)->nullable();
                $table->string('file_gambar', 255)->nullable();
                $table->unsignedInteger('urutan')->default(0);
                $table->string('status', 20)->default('aktif');
                $table->timestamps();

                $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods')->cascadeOnDelete()->restrictOnUpdate();
            });
        }

        if (Schema::hasColumn('payments', 'payment_account_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['payment_account_id']);
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payment_account_id', 'payment_method_account_id');
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('payment_method_account_id')
                    ->references('payment_method_account_id')
                    ->on('payment_method_accounts')
                    ->nullOnDelete()
                    ->restrictOnUpdate();
            });
        }
    }
};
