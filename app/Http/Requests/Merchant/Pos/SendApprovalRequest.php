<?php

namespace App\Http\Requests\Merchant\Pos;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Pos;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SendApprovalRequest extends FormRequest
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
            'phone' => 'required',
            'amount' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        /** @var User $user */
        $user = $this->user();
        $validator->after(function (Validator $validator) use ($user) {
            if (\App\Models\Core\User::query()->where('phone', $this->get('phone'))->count() < 1) {
                $validator->errors()->add('user_id', 'no user associated with this phone');
            }

            if (Pos::query()->where('merchant_id', $user->merchant_id)->where('user_id', $user->id)->count() < 1) {
                $validator->errors()->add('user_id', 'this user is not assigned to a POS yet');
            }

            if ($this->get('amount') < 1) {
                $validator->errors()->add('amount', "the minimum amount is {$user->merchant->country->currency_symbol} " . number_format(1,2));
            }
        });
    }
}
