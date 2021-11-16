<?php

namespace App\Http\Requests\Merchant\Pos;

use App\Models\Merchant\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignToBranchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {

            if (Branch::query()->where('id', $this->get('branch_id'))->count() <= 0) {
                $validator->errors()->add('branch_id', 'this branch cannot be found');
            }
        });
    }
}
