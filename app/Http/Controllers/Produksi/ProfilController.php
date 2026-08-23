<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        return view('Produksi.profil.index');
    }
}
