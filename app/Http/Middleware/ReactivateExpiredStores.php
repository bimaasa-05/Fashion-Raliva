<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReactivateExpiredStores
{
    public function handle(Request $request, Closure $next): Response
    {
        Store::autoReactivateExpired();

        return $next($request);
    }
}