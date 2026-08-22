<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DataCustomerController extends Controller
{
    public function index()
    {
        return view('Admin.customer.index');
    }
}