<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetTransaksiCommand extends Command
{
    protected $signature = 'raliva:reset-transaksi
        {--force : Jalankan tanpa konfirmasi}
        {--dry-run : Tampilkan jumlah baris tanpa menghapus}';

    protected $description = 'Reset seluruh data transaksi/finansial tanpa menghapus master data';

    /**
     * Tabel transaksi/finansial yang dihapus. Urutan child-first agar aman
     * meskipun foreign key checks dinonaktifkan.
     */
    private const DELETE_TABLES = [
        'production_order_bahan',
        'customer_wallet_transactions',
        'customer_topups',
        'customer_withdrawals',
        'wallet_transactions',
        'withdrawals',
        'commissions',
        'refund_items',
        'refunds',
        'payment_proofs',
        'payment_verifications',
        'payments',
        'shipments',
        'reviews',
        'complaint_messages',
        'complaints',
        'order_items',
        'orders',
        'checkouts',
        'store_expenses',
        'ad_slots',
        'slot_purchase_requests',
        'slot_grants',
        'store_slot_subscriptions',
        'activity_logs',
        'notifications',
    ];

    public function handle(): int
    {
        $this->warn('PERINGATAN: perintah ini menghapus data pesanan, pembayaran, refund, wallet, pengeluaran, iklan, dan transaksi lain.');
        $this->line('Master data seperti user, toko, produk, varian, gudang, stok, supplier, promo, dan pengaturan TIDAK dihapus.');
        $this->newLine();

        $counts = $this->counts();

        $this->table(
            ['Tabel', 'Aksi', 'Baris'],
            collect($counts)->map(fn (array $row, string $table) => [
                $table,
                $row['aksi'],
                number_format($row['jumlah'], 0, ',', '.'),
            ])->values()->all()
        );

        if ($this->option('dry-run')) {
            $this->info('Dry run selesai. Tidak ada data yang diubah.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Lanjutkan reset transaksi? Tindakan ini tidak bisa dibatalkan.')) {
            $this->warn('Reset dibatalkan.');

            return self::FAILURE;
        }

        $result = [];

        Schema::disableForeignKeyConstraints();

        try {
            DB::transaction(function () use (&$result) {
                if (Schema::hasTable('quality_checks')) {
                    $result['quality_checks'] = DB::table('quality_checks')
                        ->whereNotNull('order_id')
                        ->update(['order_id' => null]);
                }

                foreach (self::DELETE_TABLES as $table) {
                    if (Schema::hasTable($table)) {
                        $result[$table] = DB::table($table)->delete();
                    }
                }

                if (Schema::hasTable('wallets')) {
                    $result['wallets'] = DB::table('wallets')->update([
                        'saldo_tersedia' => 0,
                        'saldo_tertahan' => 0,
                    ]);
                }

                if (Schema::hasTable('customer_wallets')) {
                    $result['customer_wallets'] = DB::table('customer_wallets')->update([
                        'saldo_tersedia' => 0,
                    ]);
                }

                if (Schema::hasTable('warehouse_stocks')) {
                    $result['warehouse_stocks'] = DB::table('warehouse_stocks')->update([
                        'jumlah_direservasi' => 0,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            $this->error('Reset gagal: '.$exception->getMessage());

            return self::FAILURE;
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->newLine();
        $this->table(
            ['Tabel', 'Aksi', 'Baris'],
            collect($result)->map(fn (int $jumlah, string $table) => [
                $table,
                in_array($table, ['wallets', 'customer_wallets', 'warehouse_stocks'], true)
                    ? 'di-nol-kan'
                    : ($table === 'quality_checks' ? 'order_id di-null-kan' : 'dihapus'),
                number_format($jumlah, 0, ',', '.'),
            ])->values()->all()
        );

        $this->info('Reset transaksi selesai. Master data tetap dipertahankan.');

        return self::SUCCESS;
    }

    /**
     * @return array<string, array{aksi: string, jumlah: int}>
     */
    private function counts(): array
    {
        $counts = [];

        if (Schema::hasTable('quality_checks')) {
            $counts['quality_checks'] = [
                'aksi' => 'order_id di-null-kan',
                'jumlah' => DB::table('quality_checks')->whereNotNull('order_id')->count(),
            ];
        }

        foreach (self::DELETE_TABLES as $table) {
            if (Schema::hasTable($table)) {
                $counts[$table] = [
                    'aksi' => 'dihapus',
                    'jumlah' => DB::table($table)->count(),
                ];
            }
        }

        if (Schema::hasTable('wallets')) {
            $counts['wallets'] = [
                'aksi' => 'di-nol-kan',
                'jumlah' => DB::table('wallets')
                    ->where(function ($query) {
                        $query->where('saldo_tersedia', '!=', 0)
                            ->orWhere('saldo_tertahan', '!=', 0);
                    })
                    ->count(),
            ];
        }

        if (Schema::hasTable('customer_wallets')) {
            $counts['customer_wallets'] = [
                'aksi' => 'di-nol-kan',
                'jumlah' => DB::table('customer_wallets')->where('saldo_tersedia', '!=', 0)->count(),
            ];
        }

        if (Schema::hasTable('warehouse_stocks')) {
            $counts['warehouse_stocks'] = [
                'aksi' => 'di-nol-kan',
                'jumlah' => DB::table('warehouse_stocks')->where('jumlah_direservasi', '!=', 0)->count(),
            ];
        }

        return $counts;
    }
}
