<?php

namespace App\Utils\Finance\Merchant\Account;

use App\Exceptions\Merchant\AccountNotFoundException;
use App\Models\Finance\Account;
use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Utils\Finance\Merchant\TransactionUtils;
use App\Utils\General\MiscUtils;
use App\Utils\MerchantUtils;
use App\Utils\Status;

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

    /**
     * @throws AccountNotFoundException
     */
    public static function intraAccountMoneyMovement(Merchant $merchant,User $user, string $from, string $to, float $amount)
    {
        /** @var Account $fromAccount */
        $fromAccount = Account::query()->where('type', strtolower($from))
            ->where('merchant_id', $user->merchant_id)
            ->first();

        /** @var Account $toAccount */
        $toAccount = Account::query()->where('type', strtolower($to))
            ->where('merchant_id', $user->merchant_id)
            ->first();

        if (is_null($fromAccount) || is_null($toAccount))
            throw new AccountNotFoundException(
                sprintf("we could not find merchant with id: %s's %s or %s account.", $user->merchant_id, $from, $to)
            );

        $groupReference = MiscUtils::getToken(32);

        $fromAccountTransaction = MerchantUtils::createTransaction(
            $merchant,
            $user,
            TransactionUtils::TRANSACTION_DEBIT,
            $fromAccount,
            TransactionUtils::TRANSACTION_TYPE_INTRA_ACCOUNT,
            Status::STATUS_COMPLETED,
            $amount,
            $groupReference,
            MiscUtils::getToken(16),
            $user->pos
        );

        $toAccountTransaction = MerchantUtils::createTransaction(
            $merchant,
            $user,
            TransactionUtils::TRANSACTION_CREDIT,
            $toAccount,
            TransactionUtils::TRANSACTION_TYPE_INTRA_ACCOUNT,
            Status::STATUS_COMPLETED,
            $amount,
            $groupReference,
            MiscUtils::getToken(16),
            $user->pos
        );

        $fromAccount->balance -= $amount;
        $fromAccount->save();

        $toAccount->balance += $amount;
        $toAccount->save();

        $fromAccountTransaction->balance_after = $fromAccount->balance;
        $fromAccountTransaction->outstanding_balance_after = $fromAccount->outstanding_balance;
        $fromAccountTransaction->save();

        $toAccountTransaction->balance_after = $toAccount->balance;
        $toAccountTransaction->outstanding_balance_after = $toAccount->outstanding_balance;
        $toAccountTransaction->save();

    }


}
