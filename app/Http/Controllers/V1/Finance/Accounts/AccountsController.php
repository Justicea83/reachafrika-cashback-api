<?php

namespace App\Http\Controllers\V1\Finance\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Finance\Account;
use Illuminate\Http\JsonResponse;

class AccountsController extends Controller
{
    public function getAccountBalances(): JsonResponse
    {
        return $this->successResponse(Account::query()->where('merchant_id', request()->user()->merchant_id)->get());
    }
}
