<?php

namespace App\Services\Settings\SettlementBank;

use App\Models\SettlementBank;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class SettlementBankService implements ISettlementBankService
{
    private SettlementBank $settlementBankModel;

    function __construct(SettlementBank $settlementBankModel)
    {
        $this->settlementBankModel = $settlementBankModel;
    }

    public function addSettlementBank(User $user, array $payload): Model
    {
        if ($user->merchant_id == null) throw new InvalidArgumentException("you are not a merchant");

        $data = Arr::only($payload, ['account_name', 'account_no', 'bank_name']);
        $data['merchant_id'] = $user->merchant_id;
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->settlementBankModel->query()->firstOrCreate($data);

        $settlementBank->purposes()->firstOrCreate([
            'merchant_id' => $user->merchant_id,
            'purpose' => $payload['purpose']
        ]);

        return $this->getSettlementBank($user, $settlementBank->id);
    }

    private function getSettlementBank(User $user, int $id): Model
    {
        $bank = $this->settlementBankModel->query()->with(['purposes'])
            ->where('merchant_id', $user->merchant_id)
            ->find($id);
        if (is_null($bank)) throw new InvalidArgumentException("settlement bank not found");
        return $bank;
    }

    public function updateSettlementBank(User $user, int $id, array $payload)
    {
        $accountName = Arr::get($payload, 'account_name');
        $accountNo = Arr::get($payload, 'account_no');
        $bankName = Arr::get($payload, 'bank_name');
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->getSettlementBank($user, $id);
        if ($accountName != null) $settlementBank->account_name = $accountName;
        if ($accountNo != null) $settlementBank->account_no = $accountNo;
        if ($bankName != null) $settlementBank->bank_name = $bankName;

        if ($settlementBank->isDirty()) $settlementBank->save();
    }

    public function getSettlementBanks(User $user): Collection
    {
        return $this->settlementBankModel->query()->where('merchant_id', $user->merchant_id)->get();
    }

    public function removeSettlementBank(User $user, int $id)
    {
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->settlementBankModel->query()->where('merchant_id', $user->merchant_id)->where('id', $id)->first();
        if ($settlementBank == null || $settlementBank->verified) return;
        $settlementBank->delete();
    }

    public function addPurposeToSettlementBank(User $user, int $id, array $payload): Model
    {
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->getSettlementBank($user, $id);
        return $settlementBank->purposes()
            ->firstOrCreate([
                'merchant_id' => $user->merchant_id,
                'purpose' => $payload['purpose']
            ]);
    }

    public function removePurposeFromSettlementBank(User $user, int $id, int $purposedId)
    {
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->getSettlementBank($user, $id);
        if ($settlementBank->verified) return;
        $settlementBank->purposes()
            ->where('id', $purposedId)
            ->delete();
    }

    public function getSettlementBankPurposes(User $user, int $id): Collection
    {
        /** @var SettlementBank $bank */
        $bank = $this->getSettlementBank($user, $id);
        return $bank->purposes;
    }
}
