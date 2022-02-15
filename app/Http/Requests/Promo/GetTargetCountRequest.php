<?php

namespace App\Http\Requests\Promo;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $professions
 * @property mixed $interests
 * @property mixed $gender
 * @property mixed $marital_status
 * @property mixed $religion
 * @property mixed $min_age
 * @property mixed $max_age
 * @property mixed $education
 */
class GetTargetCountRequest extends FormRequest
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
            //
        ];
    }
}
