<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index()
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        return view('Gudang.profil.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'user' => auth()->user(),
        ]);
    }
}
