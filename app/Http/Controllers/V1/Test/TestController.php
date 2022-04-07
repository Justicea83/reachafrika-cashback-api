<?php

namespace App\Http\Controllers\V1\Test;

use App\Http\Controllers\Controller;
use App\Models\Finance\Account;
use App\Models\User;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TestController extends Controller
{
    function __construct()
    {
        if (!app()->environment('local', 'test')) {
            throw new InvalidArgumentException('you can only call these endpoints in test mode');
        }
    }

    function getAccountBalances(): JsonResponse
    {
        /** @var User $user */
        $user = \request()->user();
        return $this->successResponse(
            $user->merchant->accounts()->select('id','outstanding_balance','balance','currency','type')->get()
        );
    }

    function fundAccount(Request $request)
    {
        $type = $request->get('type');
        /** @var User $user */
        $user = $request->user();
        $merchant = $user->merchant;
        /** @var Account $account */
        if (in_array($type, AccountUtils::ALL_ACCOUNT_TYPES)){
            $account = $merchant->accounts()->where('type', $type)->first();
            if ($account) {
                $account->balance += 1000;
                $account->save();
            }
        }else{
            if ($type === 'outstanding_balance'){
                $merchant->normalAccount->outstanding_balance += 1000;
                $merchant->normalAccount->save();
            }
        }


    }
}
