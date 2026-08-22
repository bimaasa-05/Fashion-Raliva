<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        return view('Gudang.profil.index');
    }
}