<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Modul User & Role
            ['kode_permission' => 'user.view', 'nama_permission' => 'Melihat data user', 'deskripsi' => 'Melihat daftar dan detail user platform.'],
            ['kode_permission' => 'user.create', 'nama_permission' => 'Membuat user', 'deskripsi' => 'Menambahkan user baru.'],
            ['kode_permission' => 'user.update', 'nama_permission' => 'Mengubah data user', 'deskripsi' => 'Mengubah data dan status user.'],
            ['kode_permission' => 'role.manage', 'nama_permission' => 'Mengelola role & permission', 'deskripsi' => 'Mengatur role, permission, dan pemetaan role-permission.'],

            // Modul Store
            ['kode_permission' => 'store.view', 'nama_permission' => 'Melihat toko', 'deskripsi' => 'Melihat daftar dan detail toko sesuai scope.'],
            ['kode_permission' => 'store.create', 'nama_permission' => 'Membuat toko', 'deskripsi' => 'Mendaftarkan toko baru.'],
            ['kode_permission' => 'store.update', 'nama_permission' => 'Mengubah toko', 'deskripsi' => 'Mengubah profil toko sesuai scope.'],
            ['kode_permission' => 'store.verify', 'nama_permission' => 'Verifikasi toko', 'deskripsi' => 'Menyetujui atau menolak pendaftaran toko (platform).'],
            ['kode_permission' => 'store.staff.manage', 'nama_permission' => 'Mengelola penugasan staf toko', 'deskripsi' => 'Menugaskan/mencabut staf pada toko.'],

            // Modul Produk
            ['kode_permission' => 'product.view', 'nama_permission' => 'Melihat produk', 'deskripsi' => 'Melihat produk sesuai scope.'],
            ['kode_permission' => 'product.create', 'nama_permission' => 'Membuat produk', 'deskripsi' => 'Menambahkan produk beserta varian.'],
            ['kode_permission' => 'product.update', 'nama_permission' => 'Mengubah produk', 'deskripsi' => 'Mengubah produk/varian sesuai scope.'],
            ['kode_permission' => 'product.moderate', 'nama_permission' => 'Moderasi produk', 'deskripsi' => 'Menyetujui/menonaktifkan produk (platform).'],

            // Modul Order
            ['kode_permission' => 'order.view', 'nama_permission' => 'Melihat order', 'deskripsi' => 'Melihat order sesuai scope.'],
            ['kode_permission' => 'order.update', 'nama_permission' => 'Memproses order', 'deskripsi' => 'Mengubah status dan memproses order.'],

            // Modul Pembayaran
            ['kode_permission' => 'payment.view', 'nama_permission' => 'Melihat pembayaran', 'deskripsi' => 'Melihat status pembayaran sesuai scope.'],
            ['kode_permission' => 'payment.verify', 'nama_permission' => 'Verifikasi pembayaran', 'deskripsi' => 'Memverifikasi bukti pembayaran.'],
            ['kode_permission' => 'payment.reject', 'nama_permission' => 'Menolak pembayaran', 'deskripsi' => 'Menolak bukti pembayaran dengan alasan.'],

            // Modul Pengiriman
            ['kode_permission' => 'shipment.view', 'nama_permission' => 'Melihat pengiriman', 'deskripsi' => 'Melihat data pengiriman sesuai scope.'],
            ['kode_permission' => 'shipment.update', 'nama_permission' => 'Mengelola pengiriman', 'deskripsi' => 'Mengatur resi dan status pengiriman.'],

            // Modul Refund
            ['kode_permission' => 'refund.view', 'nama_permission' => 'Melihat refund', 'deskripsi' => 'Melihat pengajuan refund sesuai scope.'],
            ['kode_permission' => 'refund.review', 'nama_permission' => 'Meninjau refund', 'deskripsi' => 'Meninjau pengajuan refund.'],
            ['kode_permission' => 'refund.approve', 'nama_permission' => 'Menyetujui refund', 'deskripsi' => 'Menyetujui/menolak refund (platform).'],

            // Modul Wallet & Pencairan
            ['kode_permission' => 'wallet.view', 'nama_permission' => 'Melihat wallet', 'deskripsi' => 'Melihat saldo dan mutasi wallet toko.'],
            ['kode_permission' => 'withdrawal.view', 'nama_permission' => 'Melihat pencairan', 'deskripsi' => 'Melihat pengajuan pencairan dana.'],
            ['kode_permission' => 'withdrawal.request', 'nama_permission' => 'Mengajukan pencairan', 'deskripsi' => 'Mengajukan pencairan saldo toko.'],
            ['kode_permission' => 'withdrawal.approve', 'nama_permission' => 'Menyetujui pencairan', 'deskripsi' => 'Menyetujui/menolak pencairan (platform).'],

            // Modul Gudang
            ['kode_permission' => 'warehouse.view', 'nama_permission' => 'Melihat gudang & stok', 'deskripsi' => 'Melihat gudang dan stok sesuai scope.'],
            ['kode_permission' => 'warehouse.stock_in', 'nama_permission' => 'Stok masuk', 'deskripsi' => 'Mencatat barang masuk ke gudang.'],
            ['kode_permission' => 'warehouse.stock_out', 'nama_permission' => 'Stok keluar', 'deskripsi' => 'Mencatat barang keluar dari gudang.'],
            ['kode_permission' => 'warehouse.stock_adjust', 'nama_permission' => 'Penyesuaian stok', 'deskripsi' => 'Menyesuaikan stok dengan alasan.'],
            ['kode_permission' => 'warehouse.transfer', 'nama_permission' => 'Mutasi stok antar gudang', 'deskripsi' => 'Mengelola stock transfer antar gudang.'],
            ['kode_permission' => 'warehouse.damage', 'nama_permission' => 'Laporan stok rusak', 'deskripsi' => 'Melaporkan dan mencatat stok rusak/kerusakan.'],

            // Modul Produksi
            ['kode_permission' => 'production.view', 'nama_permission' => 'Melihat production order', 'deskripsi' => 'Melihat production order sesuai scope.'],
            ['kode_permission' => 'production.process', 'nama_permission' => 'Memproses produksi', 'deskripsi' => 'Memproses dan melaporkan hasil produksi.'],
            ['kode_permission' => 'production.qc', 'nama_permission' => 'Quality control', 'deskripsi' => 'Melakukan pemeriksaan kualitas hasil produksi.'],

            // Modul Promo
            ['kode_permission' => 'promotion.view', 'nama_permission' => 'Melihat promo', 'deskripsi' => 'Melihat promo sesuai scope.'],
            ['kode_permission' => 'promotion.manage', 'nama_permission' => 'Mengelola promo', 'deskripsi' => 'Membuat dan mengubah promo.'],

            // Modul Review & Komplain
            ['kode_permission' => 'review.view', 'nama_permission' => 'Melihat ulasan', 'deskripsi' => 'Melihat ulasan sesuai scope.'],
            ['kode_permission' => 'review.moderate', 'nama_permission' => 'Moderasi ulasan', 'deskripsi' => 'Menonaktifkan ulasan yang melanggar (platform).'],
            ['kode_permission' => 'complaint.view', 'nama_permission' => 'Melihat komplain', 'deskripsi' => 'Melihat komplain sesuai scope.'],
            ['kode_permission' => 'complaint.reply', 'nama_permission' => 'Membalas komplain', 'deskripsi' => 'Membalas percakapan komplain.'],

            // Modul Laporan & Pengaturan
            ['kode_permission' => 'report.view', 'nama_permission' => 'Melihat laporan', 'deskripsi' => 'Melihat laporan sesuai scope.'],
            ['kode_permission' => 'setting.manage', 'nama_permission' => 'Mengelola pengaturan platform', 'deskripsi' => 'Mengubah konfigurasi platform (komisi, metode bayar, dll).'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['kode_permission' => $permission['kode_permission']],
                [
                    'nama_permission' => $permission['nama_permission'],
                    'deskripsi' => $permission['deskripsi'],
                    'status' => 'aktif',
                ]
            );
        }
    }
}
