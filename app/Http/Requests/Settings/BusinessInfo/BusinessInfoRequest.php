<?php

namespace App\Http\Requests\Settings\BusinessInfo;

use Illuminate\Foundation\Http\FormRequest;

class BusinessInfoRequest extends FormRequest
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
            'name' => 'nullable',
            'category_id' => 'nullable',
            'description' => 'nullable',
            'website' => 'nullable',

            'head_office.country_id' => 'nullable',
            'head_office.state_id' => 'nullable',
            'head_office.town' => 'nullable',
            'head_office.building' => 'nullable',
            'head_office.street' => 'nullable',
            'head_office.address' => 'nullable',


            'social_media.facebook' => 'nullable',
            'social_media.instagram' => 'nullable',
            'social_media.twitter' => 'nullable',
        ];
    }
}
