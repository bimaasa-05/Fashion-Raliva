<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        return view('Owner.profil.index');
    }
}

