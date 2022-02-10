<?php

namespace App\Http\Controllers\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\Reports\Pos\PosSummaryReportRequest;
use App\Services\Merchant\Reports\IReportsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ReportsController extends Controller
{
    private IReportsService $reportsService;

    function __construct(IReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }

    public function posSummaryReport(PosSummaryReportRequest $request): Response
    {
        $report = $this->reportsService->posSummaryReport(
            $request->user(),
            $request->query('start-date'),
            $request->query('end-date')
        );

        $pdf = PDF::loadView('reports.merchants.pos.pos-summary-report', [
            'report' =>$report
            ]
        );

        return $pdf->download(sprintf("%s.pdf", Str::slug($report->reportTitle)));
    }

}
