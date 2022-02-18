<?php

namespace App\Http\Requests\Promo;

use App\Models\Promo\PromoDay;
use App\Models\Promo\PromoTime;
use App\Models\Promo\Schedule;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreatePromoScheduleRequest extends FormRequest
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
            'to' => 'required|numeric',
            'from' => 'required|numeric',
            'promo_day_id' => 'required|numeric',
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function ($validator) {
            /** @var User $user */
            $user = $this->user();

            if(PromoDay::query()->find($this->request->get('promo_day_id')) == null){
                $validator->errors()->add( 'promo_day_id','promo day not found');
            }
            if(PromoTime::query()->find($this->request->get('from')) == null){
                $validator->errors()->add( 'from','promo time not found');
            }
            if(PromoTime::query()->find($this->request->get('to')) == null){
                $validator->errors()->add( 'to','promo time not found');
            }

            if(
                Schedule::query()
                    ->where('to',$this->request->get('to'))
                    ->where('promo_day_id',$this->request->get('promo_day_id'))
                    ->where('from',$this->request->get('from'))
                    ->where('merchant_id',$user->merchant_id)
                    ->exists()
            ){
                $validator->errors()->add( 'schedule','this schedule already exists');
            }
        });
    }
}
