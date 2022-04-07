<?php

use App\Http\Controllers\V1\Test\TestController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/tests')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('fund-account', [TestController::class, 'fundAccount']);
        Route::get('account-balances', [TestController::class, 'getAccountBalances']);
    });
