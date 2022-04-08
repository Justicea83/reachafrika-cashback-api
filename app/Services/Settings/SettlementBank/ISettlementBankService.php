<?php

namespace App\Services\Settings\SettlementBank;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ISettlementBankService
{
    public function addSettlementBank(User $user, array $payload): Model;

    public function updateSettlementBank(User $user, int $id, array $payload);

    public function getSettlementBanks(User $user): Collection;

    public function removeSettlementBank(User $user, int $id);

    public function addPurposeToSettlementBank(User $user,int $id, array $payload): Model;

    public function removePurposeFromSettlementBank(User $user, int $id, int $purposedId);

    public function getSettlementBankPurposes(User $user, int $id): Collection;
}
