<?php

namespace App\Services\Dashboard;

use App\Entities\Responses\Dashboard\BranchSummaryResponse;
use App\Entities\Responses\Dashboard\OverviewResponse;
use App\Entities\Responses\Dashboard\PosSummaryResponse;
use App\Models\User;

interface IDashboardService
{
    public function getOverview(User $user) : OverviewResponse;
    public function branchSummary(User $user) : array;
    public function posSummary(User $user) : array;
    public function getGraphData(User $user, string $mode, array $options = [], ?string $start = null, ?string $end = null): array;
}
