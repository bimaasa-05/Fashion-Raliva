<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('Gudang.notifikasi.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'notifications' => $notifications,
        ]);
    }
}
