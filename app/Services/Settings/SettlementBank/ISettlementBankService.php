<?php

namespace App\Services\Settings\SettlementBank;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface ISettlementBankService
{
    public function addSettlementBank(User $user,array $payload) : Model;
    public function updateSettlementBank(User $user,array $payload);
    public function getSettlementBank(User $user) : ?Model;
}
