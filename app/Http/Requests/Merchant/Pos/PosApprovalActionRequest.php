<?php

namespace App\Http\Requests\Merchant\Pos;

use App\Models\Merchant\PosApproval;
use App\Models\User;
use App\Utils\PosApprovalUtils;
use App\Utils\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PosApprovalActionRequest extends FormRequest
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
            'action' => 'required',
            'request_id' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        /** @var User $user */
        $user = $this->user();
        $validator->after(function (Validator $validator) use ($user) {
            /** @var PosApproval $posApproval */
            $posApproval = PosApproval::query()->find($this->request->get('request_id'));

            if ($posApproval == null || $user->pos->id != $posApproval->pos_id || $posApproval->status != Status::STATUS_PENDING) {
                $validator->errors()->add('request_id', 'approval request not found');
            }

            if (!in_array($this->request->get('action'), PosApprovalUtils::ACTIONS)) {
                $validator->errors()->add('action', 'action not found');
            }
        });
    }

}
