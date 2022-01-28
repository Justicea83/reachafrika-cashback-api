<?php

namespace App\Utils;

use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Model;

class MerchantUtils
{
    const MERCHANT_STATUS_ACTIVE = 'active';
    const MERCHANT_STATUS_SUSPENDED = 'suspended';
    const MERCHANT_STATUS_PENDING = 'pending';
    const MERCHANT_STATUS_DELETE_REQUESTED = 'delete_requested';

    const MERCHANT_STATUSES = [
        self::MERCHANT_STATUS_ACTIVE,
        self::MERCHANT_STATUS_DELETE_REQUESTED,
        self::MERCHANT_STATUS_PENDING,
        self::MERCHANT_STATUS_SUSPENDED
    ];

    public static function findById(int $id) : ?Model{
        return Merchant::query()->find($id);
    }
}
