<?php

namespace App\Providers;

use App\Models\Warehouse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Batasi percobaan login per kombinasi email + IP (5x per menit).
        RateLimiter::for('login', function (Request $request) {
            $key = Str::transliterate(Str::lower((string) $request->input('email')).'|'.$request->ip());

            return Limit::perMinute(5)->by($key)->response(function () use ($request) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.']);
            });
        });

        // Sediakan daftar gudang & gudang aktif ke seluruh view layout Gudang,
        // agar dropdown "Ganti Gudang" bisa tampil di semua halaman role Gudang.
        \Illuminate\Support\Facades\View::composer(
            ['layouts.gudang', 'Gudang.*'],
            function (View $view) {
                if (! Auth::check() || Auth::user()->role?->nama_role !== 'Gudang') {
                    return;
                }

                $assigned = Auth::user()
                    ->assignedWarehouses()
                    ->wherePivot('status', 'aktif')
                    ->where('warehouses.status', Warehouse::STATUS_AKTIF)
                    ->orderBy('nama_gudang')
                    ->get();

                $active = null;
                if (! $assigned->isEmpty()) {
                    $activeId = Session::get('gudang_active_warehouse_id');
                    if ($activeId && $assigned->contains('warehouse_id', $activeId)) {
                        $active = $assigned->firstWhere('warehouse_id', $activeId);
                    } else {
                        Session::put('gudang_active_warehouse_id', $assigned->first()->warehouse_id);
                        $active = $assigned->first();
                    }
                }

                $view->with([
                    'warehouses' => $assigned,
                    'warehouse' => $active,
                ]);
            }
        );

        // Directive Blade @permission('kode') ... @endpermission untuk
        // menyembunyikan bagian UI (tombol/menu) bagi user tanpa izin.
        Blade::if('permission', function (string $kode) {
            return Auth::check() && Auth::user()->hasPermission($kode);
        });

        // Sediakan notifikasi terbaru + jumlah belum dibaca untuk semua layout
        // (superadmin, admin, owner, gudang, produksi) lewat partial notification-panel.
        \Illuminate\Support\Facades\View::composer(
            ['partials.notification-panel'],
            \App\View\Composers\NotificationComposer::class
        );
    }
}
