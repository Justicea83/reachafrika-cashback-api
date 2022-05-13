<?php

namespace App\Http\Requests\Settings\SettlementBank;

use App\Models\Settlements\SettlementBankPurpose;
use App\Models\User;
use App\Utils\SettlementBankUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateSettlementBankPurposeRequest extends FormRequest
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
            'purpose' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            /** @var User $user */
            $user = $this->user();

            if ($this->request->get('purpose') != null) {
                $settlementBankPurpose = SettlementBankPurpose::query()->where('merchant_id', $user->merchant_id)->where('purpose', $this->request->get('purpose'))->first();
                if (
                    !is_null($settlementBankPurpose)
                ) {
                    $validator->errors()->add("purpose", sprintf("a settlement bank with '%s' purpose already exists.", $this->request->get('purpose')));
                }

                if (!in_array($this->request->get('purpose'), SettlementBankUtils::SETTLEMENT_PURPOSES))
                    $validator->errors()->add("purpose", sprintf("allowed purposes are: %s", implode(',', SettlementBankUtils::SETTLEMENT_PURPOSES)));
            }
        });
    }
}
