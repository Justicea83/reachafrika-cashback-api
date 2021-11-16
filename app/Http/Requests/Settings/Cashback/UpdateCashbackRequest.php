<?php

namespace App\Http\Requests\Settings\Cashback;

use App\Models\Settings\Cashback;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCashbackRequest extends FormRequest
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
            'is_fixed' => 'required',
            'branch_id' => 'required',
            'bonus_percentage' => 'exclude_unless:is_fixed,false|required|numeric|min:1|max:100',
            'start' => 'exclude_unless:is_fixed,false|required|numeric',
            'end' => 'exclude_unless:is_fixed,false|required|numeric',
            'fixed_bonus' => 'exclude_if:is_fixed,false|required|numeric',
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
        $id = $this->route('id');

        $validator->after(function (Validator $validator) use ($id) {
            if ($this->request->get('is_fixed')) {
                if (
                    Cashback::query()->where('merchant_id', request()->user()->merchant_id)
                        ->where('id', '<>', $id)
                        ->where('branch_id', $this->request->get('branch_id'))
                        ->where('fixed_bonus', $this->request->get('fixed_bonus'))
                        ->count() > 0
                )
                    $validator->errors()->add('cashback', 'a cashback with this bonus already exists');
            } else {
                $cashbacks = Cashback::query()->where('merchant_id', request()->user()->merchant_id)
                    ->where('branch_id', $this->request->get('branch_id'))
                    ->where('id', '<>', $id)
                    ->where('is_fixed',false)
                    ->whereNotNull(['start','end'])
                    ->select(['id','start','end'])
                    ->get();
                /** @var Cashback $cashback */
                foreach ($cashbacks as $cashback){
                    if(
                        $this->isBetween($cashback->start,$cashback->end,$this->request->get('start'),$this->request->get('end'))
                    ){
                        $validator->errors()->add('cashback', "invalid 'start' and 'end' range");
                        break;
                    }
                }
            }
        });
    }

    private function isBetween($start,$end,$searchStart,$searchEnd): bool{
        return ($searchStart >= $start && $searchStart <= $end)
            ||
            ($searchEnd >= $start && $searchEnd <= $end)
            ||
            ($start >= $searchStart && $start <= $searchEnd)
            ||
            ($end >= $searchStart && $end <= $searchEnd)
            ;
    }
}
