<?php

namespace App\Http\Controllers\V1\Settlements;

use App\Events\Merchant\Setup\MerchantSetup;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settlements\WithdrawOutstandingBalanceRequest;
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
        MerchantSetup::dispatch($merchantId);
        return $this->noContent();
    }

    public function withdrawOutstandingBalance(WithdrawOutstandingBalanceRequest $request)
    {
        $this->settlementService->withdrawOutstandingBalance($request->user(), $request->get('amount'));
    }
}
