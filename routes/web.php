<?php

use App\Http\Controllers\Admin\DashboardOperasionalController;
use App\Http\Controllers\Admin\DataCustomerController;
use App\Http\Controllers\Admin\DataPesananController as AdminDataPesananController;
use App\Http\Controllers\Admin\DataProdukController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\KoordinasiGudangController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PengembalianDanaController as AdminPengembalianDanaController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\PermintaanProduksiController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\RiwayatAktivitasController as AdminRiwayatAktivitasController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Gudang\BarangKeluarController as GudangBarangKeluarController;
use App\Http\Controllers\Gudang\BarangMasukController as GudangBarangMasukController;
use App\Http\Controllers\Gudang\DashboardController as GudangDashboardController;
use App\Http\Controllers\Gudang\GantiGudangController;
use App\Http\Controllers\Gudang\NotifikasiController as GudangNotifikasiController;
use App\Http\Controllers\Gudang\PelangganRequestController as GudangPelangganRequestController;
use App\Http\Controllers\Gudang\PemeriksaanStokController as GudangPemeriksaanStokController;
use App\Http\Controllers\Gudang\PemindahanStokController as GudangPemindahanStokController;
use App\Http\Controllers\Gudang\ProfilController as GudangProfilController;
use App\Http\Controllers\Gudang\RiwayatStokController as GudangRiwayatStokController;
use App\Http\Controllers\Gudang\StokController as GudangStokController;
use App\Http\Controllers\Gudang\StokRusakController as GudangStokRusakController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\DataPelangganController;
use App\Http\Controllers\Owner\DataTokoController;
use App\Http\Controllers\Owner\GudangController as OwnerGudangController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Owner\KelolaSlotController;
use App\Http\Controllers\Owner\KomplainController as OwnerKomplainController;
use App\Http\Controllers\Owner\LaporanController as OwnerLaporanController;
use App\Http\Controllers\Owner\ModerasiProdukController as OwnerModerasiProdukController;
use App\Http\Controllers\Owner\NotifikasiController as OwnerNotifikasiController;
use App\Http\Controllers\Owner\PaketSlotController as OwnerPaketSlotController;
use App\Http\Controllers\Owner\PencairanDanaController as OwnerPencairanDanaController;
use App\Http\Controllers\Owner\PengajuanTokoController;
use App\Http\Controllers\Owner\PengaturanTokoController;
use App\Http\Controllers\Owner\PengembalianDanaController as OwnerPengembalianDanaController;
use App\Http\Controllers\Owner\PengirimanController as OwnerPengirimanController;
use App\Http\Controllers\Owner\PesananController as OwnerPesananController;
use App\Http\Controllers\Owner\ProdukController as OwnerProdukController;
use App\Http\Controllers\Owner\ProduksiController as OwnerProduksiController;
use App\Http\Controllers\Owner\ProfilController as OwnerProfilController;
use App\Http\Controllers\Owner\PromoController as OwnerPromoController;
use App\Http\Controllers\Owner\SaldoController;
use App\Http\Controllers\Owner\UlasanController;
use App\Http\Controllers\Produksi\BahanProduksiController as ProduksiBahanController;
use App\Http\Controllers\Produksi\BarangRusakController as ProduksiBarangRusakController;
use App\Http\Controllers\Produksi\DashboardController as ProduksiDashboardController;
use App\Http\Controllers\Produksi\DataProduksiController as ProduksiDataController;
use App\Http\Controllers\Produksi\NotifikasiController as ProduksiNotifikasiController;
use App\Http\Controllers\Produksi\PemeriksaanKualitasController as ProduksiPemeriksaanController;
use App\Http\Controllers\Produksi\PermintaanProduksiController as ProduksiPermintaanController;
use App\Http\Controllers\Produksi\ProdukSelesaiController as ProduksiProdukSelesaiController;
use App\Http\Controllers\Produksi\ProfilController as ProduksiProfilController;
use App\Http\Controllers\Produksi\RiwayatProduksiController as ProduksiRiwayatController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DataBankController;
use App\Http\Controllers\SuperAdmin\DataPembayaranController;
use App\Http\Controllers\SuperAdmin\DataPesananController;
use App\Http\Controllers\SuperAdmin\GudangController;
use App\Http\Controllers\SuperAdmin\KategoriProdukController;
use App\Http\Controllers\SuperAdmin\KomisiGlobalController;
use App\Http\Controllers\SuperAdmin\KomplainController as SaKomplainController;
use App\Http\Controllers\SuperAdmin\KurirController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\ManajemenPenggunaController;
use App\Http\Controllers\SuperAdmin\ManajemenTokoController;
use App\Http\Controllers\SuperAdmin\ModerasiProdukController;
use App\Http\Controllers\SuperAdmin\NotifikasiController;
use App\Http\Controllers\SuperAdmin\PajakBiayaController;
use App\Http\Controllers\SuperAdmin\PengaturanSistemController;
use App\Http\Controllers\SuperAdmin\PengembalianDanaController;
use App\Http\Controllers\SuperAdmin\PengirimanController as SaPengirimanController;
use App\Http\Controllers\SuperAdmin\PeringkatController;
use App\Http\Controllers\SuperAdmin\PeringkatIklanController;
use App\Http\Controllers\SuperAdmin\PermintaanPenarikanController;
use App\Http\Controllers\SuperAdmin\ProdukController;
use App\Http\Controllers\SuperAdmin\ProduksiController;
use App\Http\Controllers\SuperAdmin\ProfilController;
use App\Http\Controllers\SuperAdmin\PromoPlatformController;
use App\Http\Controllers\SuperAdmin\RiwayatAktivitasController;
use App\Http\Controllers\SuperAdmin\SaldoTokoController;
use App\Http\Controllers\SuperAdmin\StokController as SaStokController;
use App\Http\Controllers\SuperAdmin\StoreStaffController;
use App\Http\Controllers\SuperAdmin\UlasanProdukTokoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/* ===== Unified Authentication (semua role) ===== */
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');
Route::get('/forgot-password', fn () => view('customer.auth.forgot-password'))->name('password.request');
Route::get('/reset-password', fn () => view('customer.auth.reset-password'))->name('password.reset');

// customer
Route::prefix('customer')->name('customer.')->group(function () {
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

    Route::middleware('role:Customer')->group(function () {
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

        Route::get('/settings', function () {
            return view('customer.settings.index');
        })->name('settings');

        Route::get('/wishlist', function () {
            return view('customer.wishlist.index');
        })->name('wishlist');
    });

    Route::get('/help', function () {
        return view('customer.help.index');
    })->name('help');

    Route::post('/locale', function (Request $request) {
        $validated = $request->validate([
            'locale' => 'required|in:en,id',
        ]);
        session(['locale' => $validated['locale']]);

        return back();
    })->name('locale.switch');
});
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manajemen-pengguna', [ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
    Route::post('/manajemen-pengguna', [ManajemenPenggunaController::class, 'store'])->name('manajemen-pengguna.store');
    Route::get('/manajemen-pengguna/{user}/detail', [ManajemenPenggunaController::class, 'getDetail'])->name('manajemen-pengguna.detail');
    Route::put('/manajemen-pengguna/{user}', [ManajemenPenggunaController::class, 'update'])->name('manajemen-pengguna.update');
    Route::delete('/manajemen-pengguna/{user}', [ManajemenPenggunaController::class, 'destroy'])->name('manajemen-pengguna.destroy');
    Route::put('/manajemen-pengguna/{user}/role', [ManajemenPenggunaController::class, 'updateRole'])->name('manajemen-pengguna.role');
    Route::put('/manajemen-pengguna/{user}/nonaktifkan', [ManajemenPenggunaController::class, 'nonaktifkan'])->name('manajemen-pengguna.nonaktifkan');
    Route::get('/manajemen-toko', [ManajemenTokoController::class, 'index'])->name('manajemen-toko');
    Route::post('/manajemen-toko/{toko}/setujui', [ManajemenTokoController::class, 'setujui'])->name('manajemen-toko.setujui');
    Route::post('/manajemen-toko/{toko}/tolak', [ManajemenTokoController::class, 'tolak'])->name('manajemen-toko.tolak');
    Route::post('/manajemen-toko/{toko}/tangguhkan', [ManajemenTokoController::class, 'tangguhkan'])->name('manajemen-toko.tangguhkan');
    Route::post('/manajemen-toko/{toko}/aktifkan', [ManajemenTokoController::class, 'aktifkan'])->name('manajemen-toko.aktifkan');
    Route::get('/moderasi-produk', [ModerasiProdukController::class, 'index'])->name('moderasi-produk');
    Route::post('/moderasi-produk/{produk}/setujui', [ModerasiProdukController::class, 'setujui'])->name('moderasi-produk.setujui');
    Route::post('/moderasi-produk/{produk}/tolak', [ModerasiProdukController::class, 'tolak'])->name('moderasi-produk.tolak');
    Route::get('/kategori-produk', [KategoriProdukController::class, 'index'])->name('kategori-produk');
    Route::post('/kategori-produk', [KategoriProdukController::class, 'store'])->name('kategori-produk.store');
    Route::post('/kategori-produk/{kategori}/update', [KategoriProdukController::class, 'update'])->name('kategori-produk.update');
    Route::post('/kategori-produk/{kategori}/hapus', [KategoriProdukController::class, 'hapus'])->name('kategori-produk.hapus');
    Route::get('/data-pesanan', [DataPesananController::class, 'index'])->name('data-pesanan');
    Route::get('/data-pembayaran', [DataPembayaranController::class, 'index'])->name('data-pembayaran');
    Route::get('/pengembalian-dana', [PengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::post('/pengembalian-dana/{refund}/setujui', [PengembalianDanaController::class, 'setujui'])->name('pengembalian-dana.setujui');
    Route::post('/pengembalian-dana/{refund}/tolak', [PengembalianDanaController::class, 'tolak'])->name('pengembalian-dana.tolak');
    Route::post('/pengembalian-dana/{refund}/selesaikan', [PengembalianDanaController::class, 'selesaikan'])->name('pengembalian-dana.selesaikan');
    Route::get('/permintaan-penarikan', [PermintaanPenarikanController::class, 'index'])->name('permintaan-penarikan');
    Route::post('/permintaan-penarikan/{penarikan}/setujui', [PermintaanPenarikanController::class, 'setujui'])->name('permintaan-penarikan.setujui');
    Route::post('/permintaan-penarikan/{penarikan}/tolak', [PermintaanPenarikanController::class, 'tolak'])->name('permintaan-penarikan.tolak');
    Route::post('/permintaan-penarikan/{penarikan}/tandai-dibayar', [PermintaanPenarikanController::class, 'tandaiDibayar'])->name('permintaan-penarikan.tandai-dibayar');
    Route::get('/komisi-global', [KomisiGlobalController::class, 'index'])->name('komisi-global');
    Route::put('/komisi-global', [KomisiGlobalController::class, 'update'])->name('komisi-global.update');
    Route::get('/pajak-biaya', [PajakBiayaController::class, 'index'])->name('pajak-biaya');
    Route::put('/pajak-biaya', [PajakBiayaController::class, 'updatePajak'])->name('pajak-biaya.update-pajak');
    Route::get('/promo-platform', [PromoPlatformController::class, 'index'])->name('promo-platform');
    Route::post('/promo-platform', [PromoPlatformController::class, 'store'])->name('promo-platform.store');
    Route::get('/promo-platform/{promo}/detail', [PromoPlatformController::class, 'getDetail'])->name('promo-platform.detail');
    Route::put('/promo-platform/{promo}', [PromoPlatformController::class, 'update'])->name('promo-platform.update');
    Route::delete('/promo-platform/{promo}', [PromoPlatformController::class, 'destroy'])->name('promo-platform.destroy');
    Route::get('/peringkat-iklan', [PeringkatIklanController::class, 'index'])->name('peringkat-iklan');
    Route::post('/peringkat-iklan', [PeringkatIklanController::class, 'store'])->name('peringkat-iklan.store');
    Route::delete('/peringkat-iklan/{slot}', [PeringkatIklanController::class, 'destroy'])->name('peringkat-iklan.hapus');
    Route::get('/data-bank', [DataBankController::class, 'index'])->name('data-bank');
    Route::post('/data-bank', [DataBankController::class, 'store'])->name('data-bank.store');
    Route::post('/data-bank/{bank}/update', [DataBankController::class, 'update'])->name('data-bank.update');
    Route::post('/data-bank/{bank}/hapus', [DataBankController::class, 'hapus'])->name('data-bank.hapus');
    Route::get('/kurir', [KurirController::class, 'index'])->name('kurir');
    Route::post('/kurir', [KurirController::class, 'store'])->name('kurir.store');
    Route::post('/kurir/{kurir}/update', [KurirController::class, 'update'])->name('kurir.update');
    Route::post('/kurir/{kurir}/hapus', [KurirController::class, 'hapus'])->name('kurir.hapus');
    Route::post('/kurir/layanan', [KurirController::class, 'storeLayanan'])->name('kurir.layanan.store');
    Route::post('/kurir/layanan/{layanan}/update', [KurirController::class, 'updateLayanan'])->name('kurir.layanan.update');
    Route::post('/kurir/layanan/{layanan}/hapus', [KurirController::class, 'hapusLayanan'])->name('kurir.layanan.hapus');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/peringkat', [PeringkatController::class, 'index'])->name('peringkat');
    Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
    Route::post('/notifikasi/tandai-dibaca', [NotifikasiController::class, 'markRead'])->name('notifikasi.tandai-dibaca');
    Route::get('/pengaturan-sistem', [PengaturanSistemController::class, 'index'])->name('pengaturan-sistem');
    Route::put('/pengaturan-sistem', [PengaturanSistemController::class, 'updateSettings'])->name('pengaturan-sistem.update');
    Route::post('/pengaturan-sistem/legal', [PengaturanSistemController::class, 'updateLegal'])->name('pengaturan-sistem.legal');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::match(['put', 'post'], '/profil', [ProfilController::class, 'updateProfile'])->name('profil.update');
    Route::match(['put', 'post'], '/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('/komplain', [SaKomplainController::class, 'index'])->name('komplain');
    Route::post('/komplain/{komplain}/eskalasi', [SaKomplainController::class, 'eskalasi'])->name('komplain.eskalasi');
    Route::post('/komplain/{komplain}/tutup', [SaKomplainController::class, 'tutup'])->name('komplain.tutup');
    Route::get('/pengiriman', [SaPengirimanController::class, 'index'])->name('pengiriman');
    Route::put('/pengiriman/{pengiriman}/status', [SaPengirimanController::class, 'updateStatus'])->name('pengiriman.status');
    Route::get('/stok', [SaStokController::class, 'index'])->name('stok');
    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi');
    Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');
    Route::get('/saldo-toko', [SaldoTokoController::class, 'index'])->name('saldo-toko');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/ulasan-produk-toko', [UlasanProdukTokoController::class, 'index'])->name('ulasan-produk-toko');
    Route::put('/ulasan-produk-toko/{review}/nonaktifkan', [UlasanProdukTokoController::class, 'nonaktifkan'])->name('ulasan-produk-toko.nonaktifkan');
    Route::put('/ulasan-produk-toko/{review}/aktifkan', [UlasanProdukTokoController::class, 'aktifkan'])->name('ulasan-produk-toko.aktifkan');
    Route::get('/store-staff', [StoreStaffController::class, 'index'])->name('store-staff');
    Route::post('/store-staff', [StoreStaffController::class, 'store'])->name('store-staff.store');
    Route::get('/store-staff/{staff}', [StoreStaffController::class, 'show'])->name('store-staff.show');
    Route::put('/store-staff/{staff}', [StoreStaffController::class, 'update'])->name('store-staff.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [DashboardOperasionalController::class, 'index'])->name('dashboard');
    Route::get('/pesanan', [AdminDataPesananController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/{pesanan}/proses', [AdminDataPesananController::class, 'proses'])->name('pesanan.proses');
    Route::post('/pesanan/{pesanan}/batalkan', [AdminDataPesananController::class, 'batalkan'])->name('pesanan.batalkan');
    Route::get('/verifikasi-pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('verifikasi-pembayaran');
    Route::post('/verifikasi-pembayaran/{pembayaran}/setujui', [VerifikasiPembayaranController::class, 'setujui'])->name('verifikasi-pembayaran.setujui');
    Route::post('/verifikasi-pembayaran/{pembayaran}/tolak', [VerifikasiPembayaranController::class, 'tolak'])->name('verifikasi-pembayaran.tolak');
    Route::get('/customer', [DataCustomerController::class, 'index'])->name('customer');
    Route::get('/produk', [DataProdukController::class, 'index'])->name('produk');
    Route::get('/supplier', fn () => view('Admin.supplier.supplier'))->name('supplier');
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman');
    Route::post('/pengiriman/{pesanan}/resi', [PengirimanController::class, 'simpanResi'])->name('pengiriman.resi');
    Route::post('/pengiriman/{pengiriman}/kirim', [PengirimanController::class, 'kirim'])->name('pengiriman.kirim');
    Route::get('/pengembalian-dana', [AdminPengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');
    Route::get('/promo', [PromoController::class, 'index'])->name('promo');
    Route::get('/permintaan-produksi', [PermintaanProduksiController::class, 'index'])->name('permintaan-produksi');
    Route::get('/koordinasi-gudang', [KoordinasiGudangController::class, 'index'])->name('koordinasi-gudang');
    Route::get('/profil', [AdminProfilController::class, 'index'])->name('profil');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
    Route::get('/riwayat-aktivitas', [AdminRiwayatAktivitasController::class, 'index'])->name('riwayat-aktivitas');
});

Route::prefix('gudang')->name('gudang.')->middleware(['auth', 'role:Gudang'])->group(function () {
    Route::get('/dashboard', [GudangDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stok', [GudangStokController::class, 'index'])->name('stok');
    Route::get('/barang-masuk', [GudangBarangMasukController::class, 'index'])->name('barang-masuk');
    Route::get('/barang-keluar', [GudangBarangKeluarController::class, 'index'])->name('barang-keluar');
    Route::get('/pemindahan', [GudangPemindahanStokController::class, 'index'])->name('pemindahan');
    Route::get('/pemeriksaan', [GudangPemeriksaanStokController::class, 'index'])->name('pemeriksaan');
    Route::get('/stok-rusak', [GudangStokRusakController::class, 'index'])->name('stok-rusak');
    Route::get('/riwayat-stok', [GudangRiwayatStokController::class, 'index'])->name('riwayat-stok');
    Route::get('/pelanggan-request', [GudangPelangganRequestController::class, 'index'])->name('pelanggan-request');
    Route::post('/pelanggan-request/konfirmasi', [GudangPelangganRequestController::class, 'konfirmasi'])->name('pelanggan-request.konfirmasi');
    Route::get('/notifikasi', [GudangNotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/profil', [GudangProfilController::class, 'index'])->name('profil');
    Route::post('/ganti-gudang', [GantiGudangController::class, 'store'])->name('ganti');
});

Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:Owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-toko', [DataTokoController::class, 'index'])->name('data-toko');
    Route::put('/data-toko', [DataTokoController::class, 'update'])->name('data-toko.update');
    Route::get('/pengajuan-toko', [PengajuanTokoController::class, 'index'])->name('pengajuan-toko');
    Route::post('/pengajuan-toko', [PengajuanTokoController::class, 'store'])->name('pengajuan-toko.store');
    Route::get('/pengaturan-toko', [PengaturanTokoController::class, 'index'])->name('pengaturan-toko');
    Route::get('/produk', [OwnerProdukController::class, 'index'])->name('produk');
    Route::get('/kelola-slot', [KelolaSlotController::class, 'index'])->name('kelola-slot');
    Route::get('/pesanan', [OwnerPesananController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/{order}/forward', [OwnerPesananController::class, 'forward'])->name('pesanan.forward');
    Route::get('/promo', [OwnerPromoController::class, 'index'])->name('promo');
    Route::post('/promo', [OwnerPromoController::class, 'store'])->name('promo.store');
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan');
    Route::get('/data-pelanggan', [DataPelangganController::class, 'index'])->name('data-pelanggan');
    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo');
    Route::post('/saldo/pengeluaran', [SaldoController::class, 'storePengeluaran'])->name('keuangan.pengeluaran.store');
    Route::post('/saldo/pencairan', [SaldoController::class, 'storePencairan'])->name('keuangan.pencairan.store');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/karyawan/{storeStaff}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{storeStaff}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::get('/laporan', [OwnerLaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [OwnerLaporanController::class, 'export'])->name('laporan.export');
    Route::get('/gudang', [OwnerGudangController::class, 'index'])->name('gudang');
    Route::get('/komplain', [OwnerKomplainController::class, 'index'])->name('komplain');
    Route::get('/moderasi-produk', [OwnerModerasiProdukController::class, 'index'])->name('moderasi-produk');
    Route::get('/paket-slot', [OwnerPaketSlotController::class, 'index'])->name('paket-slot');
    Route::get('/pencairan-dana', [OwnerPencairanDanaController::class, 'index'])->name('pencairan-dana');
    Route::get('/pengembalian-dana', [OwnerPengembalianDanaController::class, 'index'])->name('pengembalian-dana');
    Route::get('/pengiriman', [OwnerPengirimanController::class, 'index'])->name('pengiriman');
    Route::get('/produksi', [OwnerProduksiController::class, 'index'])->name('produksi');
    Route::get('/notifikasi', [OwnerNotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/profil', [OwnerProfilController::class, 'index'])->name('profil');
});

Route::prefix('produksi')->name('produksi.')->middleware(['auth', 'role:Produksi'])->group(function () {
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
