<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.profil.index');
    }
}
