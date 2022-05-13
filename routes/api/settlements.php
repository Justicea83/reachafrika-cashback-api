<?php

use App\Http\Controllers\V1\Settlements\SettlementsController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/settlements')
    ->group(function () {
        Route::middleware('guest')->group(function(){
            Route::prefix('sub-accounts')->group(function () {
                Route::get('setup/{merchantId}', [SettlementsController::class, 'setupSubAccounts']);
            });
        });

        Route::middleware('auth:api')->group(function(){
            Route::post('withdraw-outstanding-balance', [SettlementsController::class, 'withdrawOutstandingBalance']);
        });
    });
