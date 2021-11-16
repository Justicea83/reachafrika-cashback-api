<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantRequest extends FormRequest
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
            'name' => 'required',
            'primary_email' => 'email:rfc,dns|nullable',
            'primary_phone' => 'required',
            'website' => 'url|nullable',
            'about' => 'string|nullable',
        ];
    }
}
