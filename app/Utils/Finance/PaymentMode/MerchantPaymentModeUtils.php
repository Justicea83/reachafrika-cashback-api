<?php

namespace App\Utils\Finance\PaymentMode;

use App\Models\Finance\MerchantPaymentMode;
use Illuminate\Database\Eloquent\Model;

class MerchantPaymentModeUtils
{

    public static function findById(int $id) : ?Model{
        return MerchantPaymentMode::query()->find($id);
    }
}
