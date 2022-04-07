<?php

namespace App\Utils;

use App\Models\Finance\Account;
use App\Models\Finance\Transaction;
use App\Models\Merchant\Merchant;
use App\Models\Merchant\Pos;
use App\Models\User;
use App\Utils\General\AppUtils;
use App\Utils\General\MiscUtils;
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

    public static function findById(int $id): ?Model
    {
        return Merchant::query()->find($id);
    }

    public static function createTransaction(Merchant $merchant,?User $user, string $type, Account $account, string $transactionType, string $status, float $amount, string $groupReference, string $reference, ?Pos $pos = null): Transaction
    {
        $transaction = new Transaction();
        $transaction->balance_before = $account->balance;
        $transaction->outstanding_balance_before = $account->outstanding_balance;
        $transaction->transaction = $type;
        $transaction->account = $account->type;
        $transaction->given_discount = 0;
        $transaction->group_reference = $groupReference;
        $transaction->status = $status;
        $transaction->platform = AppUtils::APP_PLATFORM;
        $transaction->merchant_id = $merchant->id;
        $transaction->branch_id = $pos->branch_id ?? null;
        $transaction->pos_id = $pos->id ?? null;
        $transaction->reference = $reference;
        $transaction->transaction_type = $transactionType;
        $transaction->currency = $merchant->country->currency;
        $transaction->currency_symbol = $merchant->country->currency_symbol;
        $transaction->created_by = $user->id ?? null;
        $transaction->amount = $amount;
        $transaction->save();
        return $transaction;
    }

}
