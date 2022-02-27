<?php

namespace App\Http\Controllers\V1\Dashboard;

use App\Entities\Responses\Dashboard\OverviewResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\IDashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController  extends Controller
{

    private IDashboardService $dashboardService;

    function __construct(IDashboardService $dashboardService){
        $this->dashboardService  = $dashboardService;
    }

    public function getOverview(): JsonResponse
    {
        return $this->successResponse($this->dashboardService->getOverview(request()->user()));
    }

    public function branchSummary(): JsonResponse
    {
        return $this->successResponse($this->dashboardService->branchSummary(request()->user()));
    }

    public function posSummary(): JsonResponse
    {
        return $this->successResponse($this->dashboardService->posSummary(request()->user()));
    }
}
