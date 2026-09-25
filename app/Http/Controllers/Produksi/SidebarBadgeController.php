<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Support\ProduksiBadgeCounter;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ProduksiBadgeCounter::counts());
    }
}