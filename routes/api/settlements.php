<?php

use App\Http\Controllers\V1\Settlements\SettlementsController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/settlements')
    //->middleware('auth:api')
    ->group(function () {
        //settlement banks
        Route::prefix('sub-accounts')->group(function () {
            Route::get('setup/{merchantId}', [SettlementsController::class, 'setupSubAccounts']);
        });

    });
