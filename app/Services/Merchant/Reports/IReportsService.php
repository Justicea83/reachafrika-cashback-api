<?php

namespace App\Services\Merchant\Reports;

use App\Entities\Responses\Reports\Pos\PosSummaryReport;
use App\Models\User;

interface IReportsService
{
    public function posSummaryReport(User $user, string $startDate, string $endDate): PosSummaryReport;
}
