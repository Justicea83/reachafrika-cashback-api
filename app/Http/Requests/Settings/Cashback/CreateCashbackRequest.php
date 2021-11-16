<?php

namespace App\Http\Requests\Settings\Cashback;

use Illuminate\Foundation\Http\FormRequest;

class CreateCashbackRequest extends FormRequest
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
            'is_fixed' => 'required',
            'branch_id' => 'required',
            'start' => 'exclude_unless:is_fixed,false|required|numeric',
            'end' => 'exclude_unless:is_fixed,false|required|numeric',
            'bonus_percentage' => 'exclude_unless:is_fixed,false|required|numeric',
            'fixed_bonus' => 'exclude_if:is_fixed,false|required|numeric|min:1|max:100'
        ];
    }
}
