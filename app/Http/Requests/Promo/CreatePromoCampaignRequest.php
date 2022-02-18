<?php

namespace App\Http\Requests\Promo;

use App\Models\Promo\PromoFrequency;
use App\Models\Promo\Schedule;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreatePromoCampaignRequest extends FormRequest
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


    public function rules(): array
    {
        return [
            'start' => 'required|date|after_or_equal:today',
            'end' => 'required|date|after:start',
            'type' => 'required|string|in:flyer,video',
            'title' => 'required|string',
            'budget' => 'required|numeric',
            'media' => 'required|file',
            'description' => 'required|string',
            'message' => 'required|string',
            'callback_url' => 'required|url',
            'lat' => 'numeric|nullable',
            'lng' => 'numeric|nullable',
            'promo_frequency_id' => 'required|numeric',
            'professions' => 'array|nullable',
            'schedules' => 'array|nullable',
            'interests' => 'array|nullable',
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function ($validator) {
            /** @var User $user */
            $user = $this->user();

            $merchant = $user->merchant;

            if (PromoFrequency::query()->find($this->request->get('promo_frequency_id')) == null) {
                $validator->errors()->add('promo_frequency_id', 'promo frequency not found');
            }

            if ($this->request->get('budget') > $merchant->creditAccount->balance) {
                $validator->errors()->add('balance', 'insufficient balance');
            }

            if ($this->request->get('schedules') != null) {
                foreach ($this->request->get('schedules') as $scheduleId) {
                    if (
                        Schedule::query()->where('merchant_id', $merchant->id)->where('id', $scheduleId)->first() == null
                    ) {
                        $validator->errors()->add("schedule", sprintf("schedule[%s] not found", $scheduleId));
                    }
                }
            }
        });
    }

}
