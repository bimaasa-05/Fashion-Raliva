<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = method_exists($user, 'role') ? ($user->role?->name ?? 'Owner') : 'Owner';

        return view('Owner.profil.index', compact('user', 'roleName'));
    }
}
