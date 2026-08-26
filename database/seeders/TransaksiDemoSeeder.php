<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreBankAccount;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class TransaksiDemoSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminId = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::SUPER_ADMIN))->value('user_id');
        $stores = Store::whereIn('nama_toko', [
            'LUNARA Fashion', 'NOIRÉ Studio', 'KAYANA Apparel', 'Velvet Closet', 'MAÉVA House',
        ])->get()->keyBy('nama_toko');

        $wallets = $this->seedWallets($stores);
        $bankAccounts = $this->seedBankAccounts($stores);
        $this->seedWithdrawals($stores, $wallets, $bankAccounts, $superAdminId);
        $this->seedPaymentsAndRefunds($stores, $superAdminId);
        $this->seedComplaints($stores, $superAdminId);
    }

    private function seedWallets($stores): array
    {
        $specs = [
            'LUNARA Fashion' => [25000000, 0],
            'NOIRÉ Studio' => [18000000, 0],
            'KAYANA Apparel' => [9000000, 3500000],
            'Velvet Closet' => [12000000, 0],
            'MAÉVA House' => [7000000, 0],
        ];

        $wallets = [];

        foreach ($specs as $namaToko => [$tersedia, $tertahan]) {
            $store = $stores->get($namaToko);

            if (! $store) {
                continue;
            }

            $wallet = Wallet::updateOrCreate(
                ['store_id' => $store->store_id],
                ['saldo_tersedia' => $tersedia, 'saldo_tertahan' => $tertahan]
            );

            $wallets[$namaToko] = $wallet;
        }

        return $wallets;
    }

    private function seedBankAccounts($stores): array
    {
        $specs = [
            'LUNARA Fashion' => ['bca', '1234567890'],
            'NOIRÉ Studio' => ['mandiri', '9876543210'],
            'KAYANA Apparel' => ['bri', '5550112233'],
            'Velvet Closet' => ['bni', '7788990011'],
            'MAÉVA House' => ['bsi', '4433221100'],
        ];

        $accounts = [];

        foreach ($specs as $namaToko => [$kodeBank, $nomor]) {
            $store = $stores->get($namaToko);
            $bankId = Bank::where('kode_bank', $kodeBank)->value('bank_id');

            if (! $store || ! $bankId) {
                continue;
            }

            $account = StoreBankAccount::updateOrCreate(
                ['store_id' => $store->store_id, 'nomor_rekening' => $nomor],
                [
                    'bank_id' => $bankId,
                    'nama_pemilik' => $store->owner->nama_lengkap ?? $store->nama_toko,
                    'is_primary' => true,
                    'status' => StoreBankAccount::STATUS_AKTIF,
                ]
            );

            $accounts[$namaToko] = $account;
        }

        return $accounts;
    }

    private function seedWithdrawals($stores, array $wallets, array $bankAccounts, ?int $superAdminId): void
    {
        $specs = [
            ['LUNARA Fashion', 12500000, Withdrawal::STATUS_PENDING, null],
            ['NOIRÉ Studio', 8750000, Withdrawal::STATUS_PENDING, null],
            ['Velvet Closet', 5200000, Withdrawal::STATUS_PENDING, null],
            ['KAYANA Apparel', 3500000, Withdrawal::STATUS_DISETUJUI, null],
            ['MAÉVA House', 1200000, Withdrawal::STATUS_DITOLAK, 'Nominal pencairan di bawah batas minimal yang disepakati setelah komisi dan penyesuaian. Silakan ajukan kembali dengan nominal yang memenuhi syarat.'],
        ];

        foreach ($specs as [$namaToko, $jumlah, $status, $alasan]) {
            $wallet = $wallets[$namaToko] ?? null;
            $account = $bankAccounts[$namaToko] ?? null;
            $store = $stores->get($namaToko);

            if (! $wallet || ! $account || ! $store) {
                continue;
            }

            $withdrawal = Withdrawal::updateOrCreate(
                ['store_id' => $store->store_id, 'jumlah' => $jumlah],
                [
                    'wallet_id' => $wallet->wallet_id,
                    'bank_account_id' => $account->bank_account_id,
                    'reviewed_by' => in_array($status, [Withdrawal::STATUS_DISETUJUI, Withdrawal::STATUS_DITOLAK], true) ? $superAdminId : null,
                    'status' => $status,
                    'diajukan_pada' => now()->subDays(3),
                    'ditinjau_pada' => $status === Withdrawal::STATUS_PENDING ? null : now()->subDay(),
                    'alasan_penolakan' => $alasan,
                    'dibayar_pada' => null,
                ]
            );
        }
    }

    private function seedPaymentsAndRefunds($stores, ?int $superAdminId): void
    {
        $metodeId = PaymentMethod::value('payment_method_id');

        $specs = [
            ['LUNARA Fashion', Refund::TIPE_PARTIAL, 0.3, 'Barang tidak sesuai deskripsi', Refund::STATUS_REQUESTED],
            ['NOIRÉ Studio', Refund::TIPE_FULL, 1.0, 'Paket tidak pernah tiba setelah batas estimasi pengiriman', Refund::STATUS_REQUESTED],
            ['Velvet Closet', Refund::TIPE_PARTIAL, 0.4, 'Produk rusak saat pengiriman', Refund::STATUS_REQUESTED],
            ['MAÉVA House', Refund::TIPE_FULL, 1.0, 'Varian yang dikirim salah dan tidak tersedia stok pengganti', Refund::STATUS_DISETUJUI],
            ['KAYANA Apparel', Refund::TIPE_PARTIAL, 0.5, 'Ukuran tidak sesuai pesanan', Refund::STATUS_DITOLAK],
        ];

        foreach ($specs as [$namaToko, $tipe, $rasio, $alasan, $status]) {
            $store = $stores->get($namaToko);

            if (! $store || ! $metodeId) {
                continue;
            }

            $order = Order::where('store_id', $store->store_id)
                ->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_DIKIRIM])
                ->orderBy('order_id')
                ->first();

            if (! $order) {
                continue;
            }

            $checkout = $order->checkout;

            if (! $checkout) {
                continue;
            }

            Payment::updateOrCreate(
                ['checkout_id' => $checkout->checkout_id],
                [
                    'payment_method_id' => $metodeId,
                    'jumlah' => $checkout->grand_total,
                    'status' => Payment::STATUS_TERVERIFIKASI,
                    'batas_waktu' => $order->created_at->copy()->addDay(),
                    'dibayar_pada' => $order->created_at,
                ]
            );

            $jumlahRefund = $tipe === Refund::TIPE_FULL
                ? (float) $order->grand_total
                : round((float) $order->grand_total * $rasio);

            $diajukanPada = $order->created_at->copy()->addDays(2);

            Refund::updateOrCreate(
                ['order_id' => $order->order_id],
                [
                    'payment_id' => Payment::where('checkout_id', $checkout->checkout_id)->value('payment_id'),
                    'requested_by' => $checkout->user_id,
                    'reviewed_by' => in_array($status, [Refund::STATUS_DISETUJUI, Refund::STATUS_DITOLAK], true) ? $superAdminId : null,
                    'tipe_refund' => $tipe,
                    'alasan' => $alasan,
                    'jumlah' => $jumlahRefund,
                    'status' => $status,
                    'alasan_penolakan' => $status === Refund::STATUS_DITOLAK ? 'Melewati batas waktu pengajuan refund sesuai ketentuan platform (maksimal 7 hari setelah barang diterima).' : null,
                    'diajukan_pada' => $diajukanPada,
                    'selesai_pada' => null,
                ]
            );
        }
    }

    private function seedComplaints($stores, ?int $superAdminId): void
    {
        $specs = [
            ['LUNARA Fashion', 'Sarah Jenkins', Complaint::KATEGORI_PRODUK, 'Ukuran tidak sesuai dengan deskripsi', 'Ukuran M yang saya terima lebih kecil dari chart ukuran di halaman produk.', Complaint::STATUS_OPEN],
            ['Velvet Closet', 'Andi Pratama', Complaint::KATEGORI_PENGIRIMAN, 'Barang tidak pernah tiba', 'Sudah 10 hari sejak pesanan diproses namun paket belum juga sampai dan resi tidak bergerak.', Complaint::STATUS_DIPROSES],
            ['KAYANA Apparel', 'Dewi Lestari', Complaint::KATEGORI_PRODUK, 'Kualitas bahan di bawah ekspektasi', 'Bahan jaket tipis sekali dan jahitan rapihnya kurang, tidak seperti foto produk.', Complaint::STATUS_OPEN],
            ['MAÉVA House', 'Putri Maharani', Complaint::KATEGORI_PELAYANAN, 'Respon toko sangat lambat', 'Chat sudah dibaca tapi tidak pernah dibalas selama 3 hari.', Complaint::STATUS_DITUTUP],
        ];

        foreach ($specs as [$namaToko, $namaCustomer, $kategori, $subjek, $deskripsi, $status]) {
            $store = $stores->get($namaToko);
            $customer = User::where('nama_lengkap', $namaCustomer)->first();

            if (! $store || ! $customer) {
                continue;
            }

            $order = Order::where('store_id', $store->store_id)
                ->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_DIKIRIM])
                ->orderByDesc('order_id')
                ->first();

            if (! $order) {
                continue;
            }

            $complaint = Complaint::updateOrCreate(
                ['user_id' => $customer->user_id, 'order_id' => $order->order_id, 'subjek' => $subjek],
                [
                    'store_id' => $store->store_id,
                    'kategori' => $kategori,
                    'deskripsi' => $deskripsi,
                    'status' => $status,
                    'dibuat_pada' => $order->created_at->copy()->addDays(3),
                    'diselesaikan_pada' => $status === Complaint::STATUS_DITUTUP ? now()->subDays(1) : null,
                ]
            );

            $existingMessage = ComplaintMessage::where('complaint_id', $complaint->complaint_id)
                ->where('sender_id', $customer->user_id)
                ->exists();

            if (! $existingMessage) {
                ComplaintMessage::create([
                    'complaint_id' => $complaint->complaint_id,
                    'sender_id' => $customer->user_id,
                    'pesan' => $deskripsi,
                ]);
            }

            $eskalasiAda = ComplaintMessage::where('complaint_id', $complaint->complaint_id)
                ->where('sender_id', $superAdminId)
                ->exists();

            if ($status === Complaint::STATUS_DIPROSES && $superAdminId && ! $eskalasiAda) {
                ComplaintMessage::create([
                    'complaint_id' => $complaint->complaint_id,
                    'sender_id' => $superAdminId,
                    'pesan' => 'Komplain ini dieskalasikan ke Owner toko untuk tindak lanjut segera. Mohon koordinasi dengan kurir dan berikan pembaruan kepada Customer.',
                ]);
            }
        }
    }
}
