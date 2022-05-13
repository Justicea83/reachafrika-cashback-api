<?php

namespace App\Services\Settlements;


use App\Models\User;

interface ISettlementService
{
    public function addMerchantSubAccounts(int $merchantId);

    public function withdraw(User $user);

    //this method allows merchants to move outstanding balance
    public function withdrawOutstandingBalance(User $user, float $amount);
}
