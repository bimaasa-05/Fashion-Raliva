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
use App\Http\Controllers\SuperAdmin\ProfilController;
use App\Http\Controllers\SuperAdmin\KategoriProdukController;
use App\Http\Controllers\SuperAdmin\KurirController;
use App\Http\Controllers\SuperAdmin\PajakBiayaController;
use App\Http\Controllers\SuperAdmin\PromoPlatformController;
use App\Http\Controllers\Admin\DashboardOperasionalController;
use App\Http\Controllers\Admin\DataPesananController as AdminDataPesananController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\Admin\DataCustomerController;
use App\Http\Controllers\Admin\DataProdukController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\PengembalianDanaController as AdminPengembalianDanaController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\PermintaanProduksiController;
use App\Http\Controllers\Admin\KoordinasiGudangController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;

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
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardOperasionalController::class, 'index'])->name('dashboard');
    Route::get('/pesanan', [AdminDataPesananController::class, 'index'])->name('pesanan');
    Route::get('/verifikasi-pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran');
    Route::get('/customer', [DataCustomerController::class, 'index'])->name('customer');
    Route::get('/produk', [DataProdukController::class, 'index'])->name('produk');
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman');
    Route::get('/pengembalian-dana', [AdminPengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');
    Route::get('/promo', [PromoController::class, 'index'])->name('promo');
    Route::get('/permintaan-produksi', [PermintaanProduksiController::class, 'index'])->name('permintaan-produksi');
    Route::get('/koordinasi-gudang', [KoordinasiGudangController::class, 'index'])->name('koordinasi-gudang');
    Route::get('/profil', [AdminProfilController::class, 'index'])->name('profil');
});
