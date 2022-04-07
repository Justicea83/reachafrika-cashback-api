<?php

namespace App\Http\Requests\Merchant;

use App\Models\Finance\PaymentMode;
use App\Models\User;
use App\Services\Collection\ICollectionService;
use App\Utils\CollectionUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class WithDrawOutstandingBalanceRequest extends FormRequest
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
            'payment_mode_id' => 'required',
            'amount' => 'required'
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            /** @var PaymentMode $paymentMode */
            $paymentMode = PaymentMode::query()->find($this->get('payment_mode_id'));
            if (is_null($paymentMode)) {
                $validator->errors()->add('payment_mode_id', 'payment mode not found');
                return;
            }
            $withdrawalModes = App::make(ICollectionService::class)->loadCollection(CollectionUtils::COLLECTION_TYPE_MERCHANT_WITHDRAWAL_MODES, [])->map(
                fn($item) => $item['id']
            )->toArray();
            if (!in_array($paymentMode->id, $withdrawalModes))
                $validator->errors()->add('payment_mode_id', 'payment mode not allowed for withdrawal');

            /** @var User $user */
            $user = $this->user();

            $merchant = $user->merchant;

            if ($this->get('amount') > $merchant->normalAccount->outstanding_balance)
                $validator->errors()->add(
                    'balance',
                    sprintf('insufficient funds, you have %s %s outstanding balance',
                        $merchant->country->currency_symbol,
                        number_format($merchant->normalAccount->outstanding_balance, 2)
                    )
                );
        });
    }
}
