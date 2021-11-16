<?php

namespace App\Http\Requests\Merchant\Pos;

use App\Models\Merchant\Pos;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignToUserRequest extends FormRequest
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
            'user_id' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {

            if (User::query()->where('id', $this->get('user_id'))->count() <= 0) {
                $validator->errors()->add('user_id', 'this user cannot be found');
            }

            if (Pos::query()->where('user_id', $this->get('user_id'))->where('id','<>',$this->route()->parameter('id'))->count() > 0) {
                $validator->errors()->add('user_id', 'this user is already assigned to a POS');
            }
        });
    }
}
