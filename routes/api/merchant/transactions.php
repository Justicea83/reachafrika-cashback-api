<?php

use App\Http\Controllers\V1\Merchant\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/transactions')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::get('', [TransactionsController::class, 'getTransactions']);
    });
});
