<?php

namespace App\Services\Settings\Finance\PaymentMode;

use App\Models\Finance\MerchantPaymentMode;
use App\Models\Finance\PaymentMode;
use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Utils\Finance\PaymentMode\MerchantPaymentModeUtils;
use App\Utils\Finance\PaymentMode\PaymentModeUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class PaymentModeService implements IPaymentModeService
{

    private PaymentMode $paymentModeModel;
    private MerchantPaymentMode $merchantPaymentModeModel;

    function __construct(PaymentMode $paymentModeModel, MerchantPaymentMode $merchantPaymentModeModel)
    {
        $this->paymentModeModel = $paymentModeModel;
        $this->merchantPaymentModeModel = $merchantPaymentModeModel;
    }

    public function addPaymentModeFromName(Merchant $merchant, string $name, bool $disabled = false)
    {
        /** @var PaymentMode $paymentMode */
        $paymentMode = PaymentModeUtils::findByName($name);
        if ($paymentMode == null) return;
        $this->merchantPaymentModeModel->query()->create([
            'disabled' => $disabled,
            'payment_mode_id' => $paymentMode->id,
            'merchant_id' => $merchant->id
        ]);
    }

    public function addPaymentMode(User $user, array $payload): Model
    {
        ['payment_mode_id' => $paymentModeId] = $payload;
        if ($this->merchantPaymentModeModel->query()->where('merchant_id', $user->merchant_id)->where('payment_mode_id', $paymentModeId)->count() > 0)
            throw new InvalidArgumentException('you already have this payment method');

        /** @var MerchantPaymentMode $merchantPaymentMode */
        $merchantPaymentMode =  $this->merchantPaymentModeModel->query()->create([
            'payment_mode_id' => $paymentModeId,
            'merchant_id' => $user->merchant_id
        ]);

        return $this->merchantPaymentModeModel->query()->with(['paymentMode'])->find($merchantPaymentMode->id);
    }

    public function getPaymentModes(User $user): Collection
    {
        return $this->merchantPaymentModeModel->query()->with(['paymentMode'])->get();
    }

    public function toggleActive(User $user, int $paymentModeId)
    {
        /** @var MerchantPaymentMode $merchantPaymentMode */
        $merchantPaymentMode = MerchantPaymentModeUtils::findById($paymentModeId);

        if ($merchantPaymentMode == null || $merchantPaymentMode->disabled) return;

        $merchantPaymentMode->active = !$merchantPaymentMode->active;
        $merchantPaymentMode->save();
    }

    public function removePaymentMethod(User $user, int $paymentModeId)
    {
        /** @var MerchantPaymentMode $merchantPaymentMode */
        $merchantPaymentMode = MerchantPaymentModeUtils::findById($paymentModeId);

        if ($merchantPaymentMode == null || $merchantPaymentMode->disabled) return;

        $merchantPaymentMode->forceDelete();
    }

    public function getAllPaymentModes(User $user): Collection
    {
        $merchantPaymentMethodIds = $this->merchantPaymentModeModel->query()->where('merchant_id', $user->merchant_id)->pluck('payment_mode_id');

        return $this->paymentModeModel->query()->whereNotIn('id', $merchantPaymentMethodIds)->get();
    }
}
