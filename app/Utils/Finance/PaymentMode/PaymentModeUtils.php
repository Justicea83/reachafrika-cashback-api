<?php

namespace App\Utils\Finance\PaymentMode;

use App\Models\Finance\PaymentMode;
use Illuminate\Database\Eloquent\Model;

class PaymentModeUtils
{
    const PAYMENT_MODE_CARD = 'card';
    const PAYMENT_MODE_CASH = 'cash';
    const PAYMENT_MODE_BANK = 'bank';
    const PAYMENT_MODE_MOMO = 'momo';
    const PAYMENT_MODE_REACHAFRIKA = 'reachafrika';
    const PAYMENT_MODE_EXTERNAL_POS = 'external_pos';

    public static function findByName(string $name) : ?Model{
        return PaymentMode::query()->where('name', strtolower($name))->first();
    }

    public static function findById(int $id) : ?Model{
        return PaymentMode::query()->find($id);
    }
}
