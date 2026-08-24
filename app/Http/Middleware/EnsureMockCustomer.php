<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMockCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('mock_customer')) {
            return redirect()->route('customer.login', ['redirect' => '/' . $request->path()]);
        }

        return $next($request);
    }
}
