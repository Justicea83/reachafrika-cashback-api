<?php

namespace App\Http\Requests\Settlements;

use App\Models\Settlements\SettlementBankPurpose;
use App\Models\User;
use App\Utils\SettlementBankUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class WithdrawOutstandingBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            /** @var User $user */
            $user = $this->user();

            $merchant = $user->merchant;

            if(
                SettlementBankPurpose::query()->where('purpose', SettlementBankUtils::SETTLEMENT_PURPOSE_WITHDRAWAL)->first() == null
            ){
                $validator->errors()->add(
                    'purpose',"Settlement Bank with 'withdrawal' purpose not found, please add one"
                );
            }

            if ($this->get('amount') > $merchant->normalAccount->outstanding_balance)
                $validator->errors()->add(
                    'amount',
                    sprintf('insufficient funds, you have %s %s outstanding balance',
                        $merchant->country->currency_symbol,
                        number_format($merchant->normalAccount->outstanding_balance, 2)
                    )
                );
        });
    }

}
