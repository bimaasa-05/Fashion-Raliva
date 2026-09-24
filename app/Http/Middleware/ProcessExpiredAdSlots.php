<?php

namespace App\Http\Middleware;

use App\Models\AdSlot;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessExpiredAdSlots
{
    public function handle(Request $request, Closure $next): Response
    {
        AdSlot::autoProcess();

        return $next($request);
    }
}