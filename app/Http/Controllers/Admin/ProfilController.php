<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        return view('Admin.profil.index');
    }
}