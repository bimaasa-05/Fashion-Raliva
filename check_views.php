<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$views = [
    'dashboard',
    'manajemen-pengguna',
    'manajemen-toko',
    'moderasi-produk',
    'kategori-produk',
    'paket-slot-produk',
    'data-pesanan',
    'data-pembayaran',
    'pengembalian-dana',
    'permintaan-penarikan',
    'komisi-global',
    'pajak-biaya',
    'promo-platform',
    'data-bank',
    'kurir',
    'laporan',
    'riwayat-aktivitas',
    'pengaturan-sistem',
];

foreach ($views as $v) {
    try {
        $size = strlen(view('SuperAdmin.' . $v . '.index')->render());
        echo str_pad($v, 24) . ' OK (' . $size . ' bytes)' . PHP_EOL;
    } catch (Throwable $e) {
        echo str_pad($v, 24) . ' ERROR: ' . $e->getMessage() . PHP_EOL;
    }
}
