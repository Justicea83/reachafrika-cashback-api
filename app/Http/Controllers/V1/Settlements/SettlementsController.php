<?php

namespace App\Http\Controllers\V1\Settlements;

use App\Http\Controllers\Controller;
use App\Services\Settlements\ISettlementService;
use Symfony\Component\HttpFoundation\Response;

class SettlementsController extends Controller
{
    private ISettlementService $settlementService;

    function __construct(ISettlementService $settlementService)
    {
        $this->settlementService = $settlementService;
    }

    public function setupSubAccounts(int $merchantId): Response
    {
        $this->settlementService->addMerchantSubAccounts($merchantId);
        return $this->noContent();
    }
}
