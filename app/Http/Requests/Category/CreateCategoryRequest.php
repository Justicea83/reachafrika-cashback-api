<?php

namespace App\Http\Requests\Category;

use App\Utils\CategoryUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateCategoryRequest extends FormRequest
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
            'name' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if(!in_array($this->route()->parameter('type'),CategoryUtils::ALLOWED_TYPES)){
                $validator->errors()->add($this->route()->parameter('type'). ' category','category not allowed');
            }
        });
    }
}

