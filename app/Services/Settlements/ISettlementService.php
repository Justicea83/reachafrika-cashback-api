<?php

namespace App\Services\Settlements;


interface ISettlementService
{
    public function addMerchantSubAccounts(int $merchantId);
}
