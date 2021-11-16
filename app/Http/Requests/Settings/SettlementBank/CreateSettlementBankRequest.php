<?php

namespace App\Http\Requests\Settings\SettlementBank;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettlementBankRequest extends FormRequest
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

        ];
    }
}
