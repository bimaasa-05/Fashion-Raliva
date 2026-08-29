<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataCustomerController extends Controller
{
    public function index()
    {
        $customers = User::withCount('orders')
            ->withSum('orders', 'grand_total')
            ->where('role_id', 6)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('Admin.customer.index', compact('customers'));
    }
}
