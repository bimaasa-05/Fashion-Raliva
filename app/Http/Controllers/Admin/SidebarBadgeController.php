<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminBadgeCounter;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(AdminBadgeCounter::counts());
    }
}