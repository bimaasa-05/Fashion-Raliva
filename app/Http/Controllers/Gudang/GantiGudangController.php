<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GantiGudangController extends Controller
{
    use ResolvesActiveWarehouse;

    /**
     * Simpan gudang aktif ke session (hanya boleh gudang yang ditugaskan).
     */
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => ['required', 'integer'],
        ]);

        $assigned = $this->assignedWarehouses()->pluck('warehouse_id')->all();

        if (! in_array((int) $request->warehouse_id, $assigned, true)) {
            return back()->with('toast', [
                'message' => 'Anda tidak ditugaskan ke gudang tersebut.',
                'icon' => 'gpp_maybe',
            ]);
        }

        Session::put('gudang_active_warehouse_id', (int) $request->warehouse_id);

        return back()->with('toast', [
            'message' => 'Gudang aktif telah diganti.',
            'icon' => 'swap_horiz',
        ]);
    }
}
