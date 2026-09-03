<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout dengan alamat pengiriman customer.
     */
    public function index()
    {
        $address = Auth::user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('customer.checkout.index', compact('address'));
    }
}
