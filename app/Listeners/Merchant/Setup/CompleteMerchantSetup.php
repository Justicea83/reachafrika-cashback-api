<?php

namespace App\Listeners\Merchant\Setup;


use App\Events\Merchant\Setup\MerchantSetup;
use App\Services\Settlements\ISettlementService;

class CompleteMerchantSetup
{
    private ISettlementService $settlementService;

    function __construct(ISettlementService $settlementService)
    {
        $this->settlementService = $settlementService;
    }

    public function handle(MerchantSetup $event)
    {
        $this->settlementService->addMerchantSubAccounts($event->merchantId);
    }
}
