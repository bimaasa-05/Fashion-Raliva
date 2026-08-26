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
use App\Http\Controllers\SuperAdmin\KomplainController as SaKomplainController;
use App\Http\Controllers\SuperAdmin\PengirimanController as SaPengirimanController;
use App\Http\Controllers\SuperAdmin\StokController as SaStokController;
use App\Http\Controllers\SuperAdmin\ProduksiController;
use App\Http\Controllers\SuperAdmin\GudangController;
use App\Http\Controllers\SuperAdmin\SaldoTokoController;
use App\Http\Controllers\SuperAdmin\ProdukController;
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
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\RiwayatAktivitasController as AdminRiwayatAktivitasController;
use App\Http\Controllers\Gudang\DashboardController as GudangDashboardController;
use App\Http\Controllers\Gudang\StokController as GudangStokController;
use App\Http\Controllers\Gudang\BarangMasukController as GudangBarangMasukController;
use App\Http\Controllers\Gudang\BarangKeluarController as GudangBarangKeluarController;
use App\Http\Controllers\Gudang\PemindahanStokController as GudangPemindahanStokController;
use App\Http\Controllers\Gudang\PemeriksaanStokController as GudangPemeriksaanStokController;
use App\Http\Controllers\Gudang\StokRusakController as GudangStokRusakController;
use App\Http\Controllers\Gudang\RiwayatStokController as GudangRiwayatStokController;
use App\Http\Controllers\Gudang\PelangganRequestController as GudangPelangganRequestController;
use App\Http\Controllers\Gudang\NotifikasiController as GudangNotifikasiController;
use App\Http\Controllers\Gudang\ProfilController as GudangProfilController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\DataTokoController;
use App\Http\Controllers\Owner\PengajuanTokoController;
use App\Http\Controllers\Owner\PengaturanTokoController;
use App\Http\Controllers\Owner\PesananController as OwnerPesananController;
use App\Http\Controllers\Owner\PromoController as OwnerPromoController;
use App\Http\Controllers\Owner\UlasanController;
use App\Http\Controllers\Owner\SaldoController;
use App\Http\Controllers\Owner\PencairanDanaController;
use App\Http\Controllers\Owner\PengembalianDanaController as OwnerPengembalianDanaController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Owner\LaporanController as OwnerLaporanController;
use App\Http\Controllers\Owner\DataPelangganController as OwnerDataPelangganController;
use App\Http\Controllers\Owner\KelolaSlotController;
use App\Http\Controllers\Owner\NotifikasiController as OwnerNotifikasiController;
use App\Http\Controllers\Owner\ProfilController as OwnerProfilController;
use App\Http\Controllers\Produksi\DashboardController as ProduksiDashboardController;
use App\Http\Controllers\Produksi\PermintaanProduksiController as ProduksiPermintaanController;
use App\Http\Controllers\Produksi\DataProduksiController as ProduksiDataController;
use App\Http\Controllers\Produksi\PemeriksaanKualitasController as ProduksiPemeriksaanController;
use App\Http\Controllers\Produksi\ProdukSelesaiController as ProduksiProdukSelesaiController;
use App\Http\Controllers\Produksi\BarangRusakController as ProduksiBarangRusakController;
use App\Http\Controllers\Produksi\BahanProduksiController as ProduksiBahanController;
use App\Http\Controllers\Produksi\RiwayatProduksiController as ProduksiRiwayatController;
use App\Http\Controllers\Produksi\NotifikasiController as ProduksiNotifikasiController;
use App\Http\Controllers\Produksi\ProfilController as ProduksiProfilController;

Route::get('/', function () {
    return view('welcome');
});

//customer
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', function () {
        return view('customer.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('customer.auth.register');
    })->name('register');

    Route::get('/forgot-password', function () {
        return view('customer.auth.forgot-password');
    })->name('forgot-password');

    Route::get('/reset-password', function () {
        return view('customer.auth.reset-password');
    })->name('reset-password');

    Route::get('/', function () {
        return view('customer.home.index');
    })->name('home');

    Route::get('/shop', function () {
        return view('customer.shop.index');
    })->name('shop');

    Route::get('/shop/produk/{id}', function () {
        return view('customer.shop.produk-detail');
    })->name('shop.produk-detail');

    Route::get('/shop/store/{id}', function () {
        return view('customer.shop.store-detail');
    })->name('shop.store-detail');

    Route::get('/search', function () {
        return view('customer.search.index');
    })->name('search');

    Route::get('/chart', function () {
        return view('customer.chart.index');
    })->name('chart');

    Route::get('/checkout', function () {
        return view('customer.checkout.index');
    })->name('checkout');

    Route::get('/order-tracking', function () {
        return view('customer.order-tracking.index');
    })->name('order-tracking');

    Route::get('/account', function () {
        return view('customer.account.index');
    })->name('account');

    Route::get('/account/edit', function () {
        return view('customer.account.edit');
    })->name('account.edit');

    Route::get('/account/password', function () {
        return view('customer.account.password');
    })->name('account.password');

    Route::get('/address', function () {
        return view('customer.address.index');
    })->name('address');

    Route::get('/reviews', function () {
        return view('customer.reviews.index');
    })->name('reviews');

    Route::get('/reviews/create', function () {
        return view('customer.reviews.create');
    })->name('reviews.create');

    Route::get('/reviews/edit', function () {
        return view('customer.reviews.edit');
    })->name('reviews.edit');

    Route::get('/notifications', function () {
        return view('customer.notifications.index');
    })->name('notifications');

    Route::get('/help', function () {
        return view('customer.help.index');
    })->name('help');

    Route::get('/settings', function () {
        return view('customer.settings.index');
    })->name('settings');

    Route::get('/wishlist', function () {
        return view('customer.wishlist.index');
    })->name('wishlist');

    Route::post('/locale', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'locale' => 'required|in:en,id',
        ]);
        session(['locale' => $validated['locale']]);

        return back();
    })->name('locale.switch');
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
    Route::get('/komplain', [SaKomplainController::class, 'index'])->name('komplain');
    Route::get('/pengiriman', [SaPengirimanController::class, 'index'])->name('pengiriman');
    Route::get('/stok', [SaStokController::class, 'index'])->name('stok');
    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi');
    Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');
    Route::get('/saldo-toko', [SaldoTokoController::class, 'index'])->name('saldo-toko');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
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
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
    Route::get('/riwayat-aktivitas', [AdminRiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
});

Route::prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stok', [GudangStokController::class, 'index'])->name('stok');
    Route::get('/barang-masuk', [GudangBarangMasukController::class, 'index'])->name('barang-masuk');
    Route::get('/barang-keluar', [GudangBarangKeluarController::class, 'index'])->name('barang-keluar');
    Route::get('/pemindahan', [GudangPemindahanStokController::class, 'index'])->name('pemindahan');
    Route::get('/pemeriksaan', [GudangPemeriksaanStokController::class, 'index'])->name('pemeriksaan');
    Route::get('/stok-rusak', [GudangStokRusakController::class, 'index'])->name('stok-rusak');
    Route::get('/riwayat-stok', [GudangRiwayatStokController::class, 'index'])->name('riwayat-stok');
    Route::get('/pelanggan-request', [GudangPelangganRequestController::class, 'index'])->name('pelanggan-request');
    Route::get('/notifikasi', [GudangNotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/profil', [GudangProfilController::class, 'index'])->name('profil');
});

Route::prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-toko', [DataTokoController::class, 'index'])->name('data-toko');
    Route::get('/pengajuan-toko', [PengajuanTokoController::class, 'index'])->name('pengajuan-toko');
    Route::get('/pengaturan-toko', [PengaturanTokoController::class, 'index'])->name('pengaturan-toko');
    Route::get('/kelola-slot', [KelolaSlotController::class, 'index'])->name('kelola-slot');
    Route::get('/pesanan', [OwnerPesananController::class, 'index'])->name('pesanan');
    Route::get('/promo', [OwnerPromoController::class, 'index'])->name('promo');
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan');
    Route::get('/data-pelanggan', [OwnerDataPelangganController::class, 'index'])->name('data-pelanggan');
    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo');
    Route::get('/pencairan-dana', [PencairanDanaController::class, 'index'])->name('pencairan-dana');
    Route::get('/pengembalian-dana', [OwnerPengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::get('/laporan', [OwnerLaporanController::class, 'index'])->name('laporan');
    Route::get('/notifikasi', [OwnerNotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/profil', [OwnerProfilController::class, 'index'])->name('profil');
});

Route::prefix('produksi')->name('produksi.')->group(function () {
    Route::get('/dashboard', [ProduksiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/permintaan-produksi', [ProduksiPermintaanController::class, 'index'])->name('permintaan-produksi');
    Route::get('/data-produksi', [ProduksiDataController::class, 'index'])->name('data-produksi');
    Route::get('/pemeriksaan-kualitas', [ProduksiPemeriksaanController::class, 'index'])->name('pemeriksaan-kualitas');
    Route::get('/produk-selesai', [ProduksiProdukSelesaiController::class, 'index'])->name('produk-selesai');
    Route::get('/barang-rusak', [ProduksiBarangRusakController::class, 'index'])->name('barang-rusak');
    Route::get('/bahan-produksi', [ProduksiBahanController::class, 'index'])->name('bahan-produksi');
    Route::get('/riwayat-produksi', [ProduksiRiwayatController::class, 'index'])->name('riwayat-produksi');
    Route::get('/notifikasi', [ProduksiNotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/profil', [ProduksiProfilController::class, 'index'])->name('profil');
});
