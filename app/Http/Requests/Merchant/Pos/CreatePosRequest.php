<?php

namespace App\Http\Requests\Merchant\Pos;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Pos;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreatePosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'branch_id' => 'required'
        ];
    }


    public function withValidator(Validator $validator)
    {
        /** @var User $user */
        $user = $this->user();
        $validator->after(function (Validator $validator) use ($user) {
            if (Pos::query()->where('merchant_id', $user->merchant_id)->where('user_id', $this->get('user_id'))->count() > 0) {
                $validator->errors()->add('user_id', 'this user is already assigned to a POS');
            }

            if (Pos::query()->where('merchant_id', $user->merchant_id)->where('branch_id', $this->get('branch_id'))->count() > 0) {
                $validator->errors()->add('branch_id', 'this branch is already assigned to a user');
            }

            if (Branch::query()->where('id', $this->get('branch_id'))->count() <= 0) {
                $validator->errors()->add('branch_id', 'this branch cannot be found');
            }
        });
    }
}
