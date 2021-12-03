<?php

namespace App\Http\Requests\Settings\AccountSettings;

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
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            /** @var User $user */
            $user = $this->user();
            if ($user == null) {
                $validator->errors()->add('user', 'you must be authenticated');
                return;
            }

            if ($this->request->get('old_password') == $this->request->get('new_password'))
                $validator->errors()->add('new_password', 'new password cannot be the same as the old password');

            if (!Hash::check($this->request->get('old_password'), $user->password))
                $validator->errors()->add('old_password', 'incorrect password');
        });

    }
}
