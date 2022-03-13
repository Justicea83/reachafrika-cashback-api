<?php

namespace App\Services\Settlements;


use App\Models\User;

interface ISettlementService
{
    public function addMerchantSubAccounts(int $merchantId);

    //this method allows merchants to move outstanding balance
    public function withdraw(User $user);
}
