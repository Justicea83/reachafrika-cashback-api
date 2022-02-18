<?php

namespace App\Services\Settings\Finance\PaymentMode;

use App\Models\Merchant\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IPaymentModeService
{
    public function addPaymentModeFromName(Merchant $merchant, string $name, bool $disabled = false);

    public function addPaymentMode(User $user, array $payload): Model;

    public function getPaymentModes(User $user): Collection;

    public function getAllPaymentModes(User $user): Collection;

    public function toggleActive(User $user, int $paymentModeId);

    public function removePaymentMethod(User $user, int $paymentModeId);
}
