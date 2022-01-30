<?php

namespace App\Utils\Finance\Merchant\Account;

class AccountUtils
{
    const ACCOUNT_TYPE_NORMAL = 'normal';
    const ACCOUNT_TYPE_REWARD = 'reward';
    const ACCOUNT_TYPE_ESCROW = 'escrow';
    const ACCOUNT_TYPE_CREDIT = 'credit';

    const ALL_ACCOUNT_TYPES = [
      self::ACCOUNT_TYPE_NORMAL,
      self::ACCOUNT_TYPE_REWARD,
      self::ACCOUNT_TYPE_ESCROW,
      self::ACCOUNT_TYPE_CREDIT,
    ];
}
