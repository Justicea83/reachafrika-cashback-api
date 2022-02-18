<?php

use App\Http\Controllers\V1\Finance\Accounts\AccountsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/accounts')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('balances', [AccountsController::class, 'getAccountBalances']);
    });
});
