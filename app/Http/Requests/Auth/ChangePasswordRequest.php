<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class ChangePasswordRequest extends FormRequest
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
            'new_password_confirmation' => 'required',
            'logout_of_all_other_devices' => 'required|boolean',
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
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
        /** @var User $user */
        $user = $this->user();
        $validator->after(function (Validator $validator) use ($user) {
            if (!Hash::check($this->get('current_password'), $user->password)) {
                $validator->errors()->add('current_password', 'incorrect password!');
            }
        });
    }
}
