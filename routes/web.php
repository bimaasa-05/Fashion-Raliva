<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\ManajemenTokoController;
use App\Http\Controllers\SuperAdmin\RiwayatAktivitasController;
use App\Http\Controllers\SuperAdmin\KomisiGlobalController;
use App\Http\Controllers\SuperAdmin\ModerasiProdukController;
use App\Http\Controllers\SuperAdmin\ManajemenPenggunaController;
use App\Http\Controllers\SuperAdmin\PermintaanPenarikanController;
use App\Http\Controllers\SuperAdmin\DataBankController;
use App\Http\Controllers\SuperAdmin\DataPesananController;
use App\Http\Controllers\SuperAdmin\DataPembayaranController;
use App\Http\Controllers\SuperAdmin\PengembalianDanaController;
use App\Http\Controllers\SuperAdmin\PaketSlotProdukController;
use App\Http\Controllers\SuperAdmin\PengaturanSistemController;
use App\Http\Controllers\SuperAdmin\KategoriProdukController;
use App\Http\Controllers\SuperAdmin\KurirController;
use App\Http\Controllers\SuperAdmin\PajakBiayaController;
use App\Http\Controllers\SuperAdmin\PromoPlatformController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manajemen-pengguna', [ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
    Route::get('/manajemen-toko', [ManajemenTokoController::class, 'index'])->name('manajemen-toko');
    Route::get('/moderasi-produk', [ModerasiProdukController::class, 'index'])->name('moderasi-produk');
    Route::get('/kategori-produk', [KategoriProdukController::class, 'index'])->name('kategori-produk');
    Route::get('/paket-slot-produk', [PaketSlotProdukController::class, 'index'])->name('paket-slot-produk');
    Route::get('/data-pesanan', [DataPesananController::class, 'index'])->name('data-pesanan');
    Route::get('/data-pembayaran', [DataPembayaranController::class, 'index'])->name('data-pembayaran');
    Route::get('/pengembalian-dana', [PengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::get('/permintaan-penarikan', [PermintaanPenarikanController::class, 'index'])->name('permintaan-penarikan');
    Route::get('/komisi-global', [KomisiGlobalController::class, 'index'])->name('komisi-global');
    Route::get('/pajak-biaya', [PajakBiayaController::class, 'index'])->name('pajak-biaya');
    Route::get('/promo-platform', [PromoPlatformController::class, 'index'])->name('promo-platform');
    Route::get('/data-bank', [DataBankController::class, 'index'])->name('data-bank');
    Route::get('/kurir', [KurirController::class, 'index'])->name('kurir');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
    Route::get('/pengaturan-sistem', [PengaturanSistemController::class, 'index'])->name('pengaturan-sistem');
});
