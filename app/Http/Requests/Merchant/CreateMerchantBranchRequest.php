<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class CreateMerchantBranchRequest extends FormRequest
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
            'lat' => 'numeric',
            'lng' => 'numeric',
            'opens_at' => 'required',
            'closes_at' => 'required',
            'location' => 'nullable',
        ];
    }
}
