<?php

use App\Http\Controllers\V1\Core\CoreInfoController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/core/info')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('user-info-by-phone/{phone}', [CoreInfoController::class, 'getUserInfoByPhone']);
    });
