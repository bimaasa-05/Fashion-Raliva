<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Support\GudangBadgeCounter;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(GudangBadgeCounter::counts());
    }
}