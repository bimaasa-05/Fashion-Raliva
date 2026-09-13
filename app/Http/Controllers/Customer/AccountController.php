<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return view('customer.account.guest');
        }

        $user = Auth::user();
        $role = $user->role?->nama_role;

        if ($role !== Role::CUSTOMER) {
            abort(403);
        }

        return view('customer.account.index');
    }
}
