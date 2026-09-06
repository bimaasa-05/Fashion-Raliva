<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\Refund;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Supplier
        $suppliers = [
            ['nama_supplier' => 'CV Tekstil Bandung', 'kota' => 'Bandung', 'kontak' => 'Budi Santoso', 'email' => 'budi@tekstilbandung.co.id', 'jenis' => 'kain', 'status' => 'aktif', 'catatan' => 'Minimum order 50 meter.'],
            ['nama_supplier' => 'Aksesoris Mega', 'kota' => 'Jakarta', 'kontak' => 'Sari Wijaya', 'email' => 'sari@aksesorismega.com', 'jenis' => 'aksesoris', 'status' => 'verifikasi', 'catatan' => 'Pengiriman 3-5 hari.'],
            ['nama_supplier' => 'Kemasan Prima', 'kota' => 'Surabaya', 'kontak' => 'Joko Pratomo', 'email' => 'joko@kemasanprima.id', 'jenis' => 'kemasan', 'status' => 'aktif', 'catatan' => 'Box kustom tersedia.'],
        ];
        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['email' => $s['email']], $s);
        }

        // ActivityLog (contoh riwayat aktivitas admin)
        if (ActivityLog::count() === 0) {
            $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Admin Toko'))->first() ?? User::first();
            $samples = [
                ['aksi' => 'verifikasi_pembayaran', 'deskripsi' => 'Kamu memverifikasi pembayaran <strong>#RLV-2076</strong> sebesar <strong>Rp 1.150.000</strong>.', 'target_tipe' => 'Transaksi'],
                ['aksi' => 'resi', 'deskripsi' => 'Kamu memasukkan resi <strong>JNE2608210041</strong> untuk pesanan <strong>#RLV-2075</strong>.', 'target_tipe' => 'Pengiriman'],
                ['aksi' => 'komplain', 'deskripsi' => 'Kamu membalas komplain <strong>KOM-0311</strong> dari <strong>Andi Pratama</strong>.', 'target_tipe' => 'Komplain'],
                ['aksi' => 'refund', 'deskripsi' => 'Kamu menyetujui pengembalian dana untuk pesanan <strong>#RLV-2069</strong>.', 'target_tipe' => 'Refund'],
                ['aksi' => 'produk', 'deskripsi' => 'Kamu memperbarui stok <strong>Oversized Linen Shirt</strong>.', 'target_tipe' => 'Produk'],
            ];
            foreach ($samples as $i => $s) {
                ActivityLog::create([
                    'user_id' => $admin?->user_id,
                    'aksi' => $s['aksi'],
                    'target_tipe' => $s['target_tipe'],
                    'deskripsi' => $s['deskripsi'],
                    'created_at' => now()->subHours($i * 5),
                ]);
            }
        }

        // ComplaintMessage contoh (percakapan) untuk complaint pertama
        $complaint = Complaint::first();
        $adminUser = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Admin Toko'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('nama_role', 'Admin'))->first()
            ?? User::first();
        if ($complaint && ComplaintMessage::where('complaint_id', $complaint->complaint_id)->count() === 0) {
            ComplaintMessage::create([
                'complaint_id' => $complaint->complaint_id,
                'sender_id' => $complaint->user_id,
                'pesan' => 'Halo, saya ingin komplain karena pesanan datang dalam kondisi rusak.',
            ]);
            ComplaintMessage::create([
                'complaint_id' => $complaint->complaint_id,
                'sender_id' => $adminUser?->user_id,
                'pesan' => 'Terima kasih informasinya, kami akan proses penggantian barangnya.',
            ]);
        }

        // WarehouseStock contoh (agar halaman Stok punya data)
        if (WarehouseStock::count() === 0) {
            $wh = \App\Models\Warehouse::first();
            $variants = \App\Models\ProductVariant::limit(10)->get();
            foreach ($variants as $v) {
                WarehouseStock::create([
                    'warehouse_id' => $wh->warehouse_id,
                    'product_variant_id' => $v->product_variant_id,
                    'jumlah_stok' => rand(10, 80),
                    'stok_minimum' => 5,
                ]);
            }
        }

        // Refund contoh (buat payment bila belum ada, lalu refund)
        if (Refund::count() === 0) {
            $order = Order::with('checkout')->first();
            if ($order && $order->checkout) {
                $payment = \App\Models\Payment::firstOrCreate(
                    ['checkout_id' => $order->checkout->checkout_id],
                    [
                        'payment_method_id' => \App\Models\PaymentMethod::first()?->payment_method_id ?? 1,
                        'jumlah' => $order->total_harga ?? 200000,
                        'status' => 'lunas',
                        'batas_waktu' => now()->addDay(),
                        'dibayar_pada' => now()->subDays(2),
                    ]
                );
                Refund::create([
                    'order_id' => $order->order_id,
                    'payment_id' => $payment->payment_id,
                    'requested_by' => $order->checkout->user_id,
                    'tipe_refund' => 'sebagian',
                    'alasan' => 'Barang cacat produksi, meminta pengembalian sebagian.',
                    'jumlah' => 150000,
                    'status' => Refund::STATUS_REQUESTED,
                    'diajukan_pada' => now()->subDay(),
                ]);
            }
        }
        // Verifikasi Pembayaran (contoh pembayaran menunggu verifikasi + bukti)
        if (\App\Models\Payment::where('status', \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI)->count() === 0) {
            $orders = Order::with('checkout')->whereNotNull('checkout_id')
                ->whereDoesntHave('checkout.payment')
                ->limit(3)->get();
            foreach ($orders as $o) {
                if (! $o->checkout) continue;
                $pay = \App\Models\Payment::create([
                    'checkout_id' => $o->checkout->checkout_id,
                    'payment_method_id' => \App\Models\PaymentMethod::first()?->payment_method_id ?? 1,
                    'jumlah' => $o->total_harga ?? 200000,
                    'status' => \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI,
                    'batas_waktu' => now()->addDay(),
                ]);
                \App\Models\PaymentProof::create([
                    'payment_id' => $pay->payment_id,
                    'file_bukti' => 'proofs/sample-' . $pay->payment_id . '.jpg',
                    'uploaded_at' => now(),
                ]);
            }
        }

        $this->command?->info('AdminDemoSeeder selesai: ' . Supplier::count() . ' supplier, ' . ActivityLog::count() . ' log, ' . WarehouseStock::count() . ' stok, ' . Refund::count() . ' refund, ' . \App\Models\Payment::where('status', \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI)->count() . ' verifikasi.');
    }
}
