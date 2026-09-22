<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Support\SuperAdminBadgeCounter;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SuperAdminBadgeCounter::counts());
    }
}
