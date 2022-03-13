<?php

namespace App\Services\Settlements;


interface ISettlementService
{
    public function settleMerchants();
    public function reverseTransactionForMerchant();
    public function addMerchantSubAccounts(int $merchantId);
}
