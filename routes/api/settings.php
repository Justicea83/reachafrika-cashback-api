<?php


use App\Http\Controllers\V1\Settings\CashbacksController;
use App\Http\Controllers\V1\Settings\SettingsController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/settings')
    ->middleware('auth:api')
    ->group(function () {
        //business info
        Route::post('business-info', [SettingsController::class, 'updatedBusinessInfo']);

        //settlement banks
        Route::prefix('settlement-banks')->group(function (){
            Route::post('',[SettingsController::class,'createSettlementBank']);
            Route::get('',[SettingsController::class,'getSettlementBank']);
        });

        //cashbacks
        Route::prefix('cashbacks')->group(function (){
            Route::post('',[CashbacksController::class,'createCashback']);
        });
    });
