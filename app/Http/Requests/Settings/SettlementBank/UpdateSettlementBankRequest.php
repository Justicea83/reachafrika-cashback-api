<?php

namespace App\Http\Requests\Settings\SettlementBank;

use App\Models\Finance\PaymentMode;
use App\Models\Settlements\SettlementBank;
use App\Models\Settlements\SettlementBankPurpose;
use App\Models\User;
use App\Utils\Finance\PaymentMode\PaymentModeUtils;
use App\Utils\SettlementBankUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateSettlementBankRequest extends FormRequest
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
            'bank_name' => 'required',
            'account_no' => 'required',
            'account_name' => 'required',
            'purpose' => 'required',
            'payment_mode_id' => 'required',
            'config' => 'required',
            'config.api' => 'required',
            'config.data' => 'required',
            'config.data.id' => 'required',
            'config.data.code' => 'required',
            'config.data.name' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            /** @var User $user */
            $user = $this->user();

            /** @var SettlementBank $settlementBank */
            $settlementBank = SettlementBank::query()->find($this->route('id'));

            if(is_null($settlementBank)){
                $validator->errors()->add("settlement_bank", "settlement bank not found");
                return;
            }
            if($settlementBank->verified){
                $validator->errors()->add("settlement_bank", "you cannot update an already verified settlement bank");
                return;
            }

            if ($this->request->get('purpose') != null) {
                $settlementBankPurpose = SettlementBankPurpose::query()
                    ->where('merchant_id', $user->merchant_id)
                    ->where('purpose', $this->request->get('purpose'))
                    ->where('settlement_bank_id','<>', $settlementBank->id)
                    ->first();
                if (
                    !is_null($settlementBankPurpose)
                ) {
                    $validator->errors()->add("purpose", sprintf("a settlement bank with '%s' purpose already exists.", $this->request->get('purpose')));
                }

                if (!in_array($this->request->get('purpose'), SettlementBankUtils::SETTLEMENT_PURPOSES))
                    $validator->errors()->add("purpose", sprintf("allowed purposes are: %s", implode(',', SettlementBankUtils::SETTLEMENT_PURPOSES)));
            }

            /** @var PaymentMode $paymentMode */
            $paymentMode = PaymentMode::query()->find($this->request->get('payment_mode_id'));

            if (is_null($paymentMode)) {
                $validator->errors()->add('payment_mode_id', 'payment mode not found, please check the collections');
                return;
            }
            if (
                $this->request->get('purpose') == SettlementBankUtils::SETTLEMENT_PURPOSE_COLLECTION &&
                $paymentMode->name != PaymentModeUtils::PAYMENT_MODE_BANK
            ) {
                $validator->errors()->add('purpose', 'purpose of type collection can only be used with banks.');
            }
        });
    }

}
