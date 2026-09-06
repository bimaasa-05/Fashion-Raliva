<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Refund;
use App\Models\Store;
use App\Models\StoreBankAccount;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    /**
     * Seed wallet + financial activity for the demo Owner store so the
     * Owner "Keuangan" page shows real (non-hardcoded) data.
     */
    public function run(): void
    {
        $owner = User::where('email', 'owner@raliva.test')->first();
        if (! $owner) {
            return;
        }

        $store = $owner->ownedStores()->first();
        if (! $store) {
            return;
        }

        // Avoid duplicate seeding on re-run.
        if ($store->wallet) {
            return;
        }

        $bca = Bank::where('kode_bank', 'bca')->first();
        $mandiri = Bank::where('kode_bank', 'mandiri')->first();

        $rekeningBca = StoreBankAccount::updateOrCreate(
            ['store_id' => $store->store_id, 'nomor_rekening' => '812008821'],
            [
                'bank_id' => $bca?->bank_id,
                'nama_pemilik' => $owner->nama_lengkap,
                'is_primary' => true,
                'status' => 'aktif',
            ]
        );

        StoreBankAccount::updateOrCreate(
            ['store_id' => $store->store_id, 'nomor_rekening' => '130000077'],
            [
                'bank_id' => $mandiri?->bank_id,
                'nama_pemilik' => $owner->nama_lengkap,
                'is_primary' => false,
                'status' => 'aktif',
            ]
        );

        // Sisa saldo akhir yang ingin kita tampilkan: Rp 32.500.000 tersedia.
        $saldoTersedia = 32500000;
        $saldoTertahan = 7100000;

        $wallet = Wallet::create([
            'store_id' => $store->store_id,
            'saldo_tersedia' => $saldoTersedia,
            'saldo_tertahan' => $saldoTertahan,
        ]);

        // ---- Mutasi saldo (riwayat) ----
        // Disusun dari yang lama -> baru supaya saldo_sebelum/sesudah masuk akal.
        $mutations = [
            [
                'jenis_transaksi' => WalletTransaction::JENIS_PENJUALAN_MASUK,
                'jumlah' => 3420000,
                'keterangan' => 'Pesanan selesai — Raka Aditya (#RLV-2087)',
                'wkt' => '2026-08-20 17:33:00',
            ],
            [
                'jenis_transaksi' => WalletTransaction::JENIS_PENYESUAIAN,
                'jumlah' => -1240000,
                'keterangan' => 'Biaya layanan platform Agustus (INV-BIAYA-08)',
                'wkt' => '2026-08-19 14:10:00',
            ],
            [
                'jenis_transaksi' => WalletTransaction::JENIS_PENJUALAN_MASUK,
                'jumlah' => 459000,
                'keterangan' => 'Pesanan selesai — Kevin Sanjaya (#RLV-2090)',
                'wkt' => '2026-08-21 08:20:00',
            ],
            [
                'jenis_transaksi' => WalletTransaction::JENIS_REFUND_KELUAR,
                'jumlah' => -450000,
                'keterangan' => 'Komplain selesai — refund parsial (CMP-0034)',
                'wkt' => '2026-08-21 19:55:00',
            ],
            [
                'jenis_transaksi' => WalletTransaction::JENIS_PENJUALAN_MASUK,
                'jumlah' => 1890000,
                'keterangan' => 'Pesanan selesai — Nadia Putri (#RLV-2089)',
                'wkt' => '2026-08-22 11:40:00',
            ],
            [
                'jenis_transaksi' => WalletTransaction::JENIS_WITHDRAWAL,
                'jumlah' => -25000000,
                'keterangan' => 'Pencairan dana ke BCA ****8821 (WD-0092)',
                'wkt' => '2026-07-22 15:02:00',
            ],
        ];

        // Hitung mundur saldo_sesudah agar konsisten dengan saldoTersedia akhir.
        $running = $saldoTersedia;
        $ordered = array_reverse($mutations); // dari terbaru -> terlama
        foreach ($ordered as &$m) {
            $m['saldo_sesudah'] = $running;
            $m['saldo_sebelum'] = $running - $m['jumlah'];
            $running = $m['saldo_sebelum'];
        }
        unset($m);
        $ordered = array_reverse($ordered); // kembalikan urutan tampil (lama -> baru)

        foreach ($ordered as $m) {
            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => $m['jenis_transaksi'],
                'jumlah' => $m['jumlah'],
                'saldo_sebelum' => $m['saldo_sebelum'],
                'saldo_sesudah' => $m['saldo_sesudah'],
                'keterangan' => $m['keterangan'],
                'created_at' => $m['wkt'],
                'updated_at' => $m['wkt'],
            ]);
        }

        // ---- Riwayat pencairan dana ----
        $withdrawalRows = [
            [
                'jumlah' => 25000000,
                'status' => Withdrawal::STATUS_PENDING,
                'diajukan_pada' => '2026-07-22 15:02:00',
            ],
            [
                'jumlah' => 20000000,
                'status' => Withdrawal::STATUS_DIBAYAR,
                'diajukan_pada' => '2026-07-08 10:00:00',
                'dibayar_pada' => '2026-07-09 14:00:00',
            ],
            [
                'jumlah' => 15500000,
                'status' => Withdrawal::STATUS_DIBAYAR,
                'diajukan_pada' => '2026-07-24 09:00:00',
                'dibayar_pada' => '2026-07-25 11:00:00',
            ],
            [
                'jumlah' => 30000000,
                'status' => Withdrawal::STATUS_DITOLAK,
                'diajukan_pada' => '2026-07-10 09:00:00',
                'ditinjau_pada' => '2026-07-10 12:00:00',
                'alasan_penolakan' => 'Rekening tujuan tidak cocok dengan identitas Owner',
            ],
            [
                'jumlah' => 18000000,
                'status' => Withdrawal::STATUS_DIBAYAR,
                'diajukan_pada' => '2026-06-26 09:00:00',
                'dibayar_pada' => '2026-06-27 11:00:00',
            ],
        ];

        foreach ($withdrawalRows as $w) {
            Withdrawal::create(array_merge([
                'store_id' => $store->store_id,
                'wallet_id' => $wallet->wallet_id,
                'bank_account_id' => $rekeningBca->bank_account_id,
            ], $w));
        }

        // ---- Order + Payment contoh (supaya FK refund valid & tampil di Pengembalian) ----
        $customerJane = User::where('email', 'customer@raliva.test')->first();
        $requesterId = $customerJane?->user_id ?? $owner->user_id;
        $paymentMethod = \App\Models\PaymentMethod::first();

        $refundCases = [
            ['no' => 'RLV-2085', 'tgl' => '2026-08-22 09:00:00', 'grand' => 459000, 'jml' => 459000, 'tipe' => Refund::TIPE_PARTIAL, 'alasan' => 'Barang tidak sesuai deskripsi — warna berbeda', 'status' => Refund::STATUS_REQUESTED, 'selesai' => null],
            ['no' => 'RLV-2079', 'tgl' => '2026-08-21 09:00:00', 'grand' => 789000, 'jml' => 789000, 'tipe' => Refund::TIPE_FULL, 'alasan' => 'Paket hilang dalam pengiriman', 'status' => Refund::STATUS_DISETUJUI, 'selesai' => null],
            ['no' => 'RLV-2076', 'tgl' => '2026-08-18 09:00:00', 'grand' => 320000, 'jml' => 320000, 'tipe' => Refund::TIPE_FULL, 'alasan' => 'Ukuran tidak pas', 'status' => Refund::STATUS_SELESAI, 'selesai' => '2026-08-19 09:00:00'],
            ['no' => 'RLV-2071', 'tgl' => '2026-08-15 09:00:00', 'grand' => 529000, 'jml' => 529000, 'tipe' => Refund::TIPE_FULL, 'alasan' => 'Batal sebelum dikirim', 'status' => Refund::STATUS_SELESAI, 'selesai' => '2026-08-16 09:00:00'],
            ['no' => 'RLV-2068', 'tgl' => '2026-08-12 09:00:00', 'grand' => 259000, 'jml' => 259000, 'tipe' => Refund::TIPE_PARTIAL, 'alasan' => 'Menyesal membeli (tanpa cacat produk)', 'status' => Refund::STATUS_DITOLAK, 'selesai' => null],
        ];

        foreach ($refundCases as $case) {
            $checkout = \App\Models\Checkout::create([
                'user_id' => $requesterId,
                'subtotal' => $case['grand'],
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $case['grand'],
                'status' => 'selesai',
            ]);

            $order = \App\Models\Order::create([
                'checkout_id' => $checkout->checkout_id,
                'store_id' => $store->store_id,
                'nomor_order' => $case['no'],
                'subtotal' => $case['grand'],
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $case['grand'],
                'status' => \App\Models\Order::STATUS_SELESAI,
            ]);

            $payment = \App\Models\Payment::create([
                'checkout_id' => $checkout->checkout_id,
                'payment_method_id' => $paymentMethod?->payment_method_id,
                'jumlah' => $case['grand'],
                'status' => 'dibayar',
                'batas_waktu' => $case['tgl'],
                'dibayar_pada' => $case['tgl'],
            ]);

            Refund::create([
                'order_id' => $order->order_id,
                'payment_id' => $payment->payment_id,
                'requested_by' => $requesterId,
                'tipe_refund' => $case['tipe'],
                'alasan' => $case['alasan'],
                'jumlah' => $case['jml'],
                'status' => $case['status'],
                'diajukan_pada' => $case['tgl'],
                'selesai_pada' => $case['selesai'],
            ]);
        }
    }
}
