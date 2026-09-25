<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerBadgeCounter;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(OwnerBadgeCounter::counts());
    }
}