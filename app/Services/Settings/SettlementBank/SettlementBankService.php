<?php

namespace App\Services\Settings\SettlementBank;

use App\Models\SettlementBank;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->settlementBankModel->query()->where('merchant_id', $user->merchant_id)->first();
        if ($settlementBank != null) {
            $settlementBank->account_name = $payload['account_name'];
            $settlementBank->account_no = $payload['account_no'];
            $settlementBank->bank_name = $payload['bank_name'];
            $settlementBank->save();
        } else {
            $data = Arr::only($payload, ['account_name', 'account_no', 'bank_name']);
            $data['merchant_id'] = $user->merchant_id;
            $settlementBank = $this->settlementBankModel->query()->firstOrCreate($data);
        }
        return $settlementBank;
    }

    public function updateSettlementBank(User $user, array $payload)
    {
        $accountName = Arr::get($payload,'account_name');
        $accountNo = Arr::get($payload,'account_no');
        $bankName = Arr::get($payload,'bank_name');
        /** @var SettlementBank $settlementBank */
        $settlementBank = $this->getSettlementBank($user);
        if($settlementBank == null)return;
        if($accountName != null) $settlementBank->account_name = $accountName;
        if($accountNo != null) $settlementBank->account_no = $accountNo;
        if($bankName != null) $settlementBank->bank_name = $bankName;

        if($settlementBank->isDirty())$settlementBank->save();

    }

    public function getSettlementBank(User $user): ?Model
    {
        return $this->settlementBankModel->query()->where('merchant_id',$user->merchant_id)->first();
    }
}
