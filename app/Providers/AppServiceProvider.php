<?php

namespace App\Providers;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
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
    }
}
