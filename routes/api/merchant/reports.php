<?php

use App\Http\Controllers\V1\Merchant\ReportsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/reports')->middleware('auth:api')
    ->group(function () {
        Route::prefix('pos')->group(function () {
            Route::get('summary', [ReportsController::class, 'posSummaryReport']);
        });
    });
