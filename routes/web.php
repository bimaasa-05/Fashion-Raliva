<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\ManajemenTokoController;
use App\Http\Controllers\SuperAdmin\RiwayatAktivitasController;
use App\Http\Controllers\SuperAdmin\KomisiGlobalController;
use App\Http\Controllers\SuperAdmin\MenuController;
use App\Http\Controllers\SuperAdmin\ModerasiProdukController;
use App\Http\Controllers\SuperAdmin\ManajemenPenggunaController;
use App\Http\Controllers\SuperAdmin\PermintaanPenarikanController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/manajemen-toko', [ManajemenTokoController::class, 'index'])->name('manajemen-toko');
    Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
    Route::get('/komisi-global', [KomisiGlobalController::class, 'index'])->name('komisi-global');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/moderasi-produk', [ModerasiProdukController::class, 'index'])->name('moderasi-produk');
    Route::get('/manajemen-pengguna', [ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
    Route::get('/permintaan-penarikan', [PermintaanPenarikanController::class, 'index'])->name('permintaan-penarikan');
});
