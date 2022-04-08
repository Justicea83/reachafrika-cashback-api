<?php


use App\Http\Controllers\V1\Settings\CashbacksController;
use App\Http\Controllers\V1\Settings\PaymentModesController;
use App\Http\Controllers\V1\Settings\SettingsController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/settings')
    ->middleware('auth:api')
    ->group(function () {
        //business info
        Route::post('business-info', [SettingsController::class, 'updatedBusinessInfo']);

        //settlement banks
        Route::prefix('settlement-banks')->group(function () {
            Route::post('', [SettingsController::class, 'createSettlementBank']);
            Route::put('{id}', [SettingsController::class, 'updateSettlementBank'])->whereNumber('id');
            Route::get('{id}/purposes', [SettingsController::class, 'getSettlementBankPurposes'])->whereNumber('id');
            Route::post('{id}/purposes', [SettingsController::class, 'createSettlementBankPurpose'])->whereNumber('id');
            Route::delete('{id}/purposes/{purposeId}', [SettingsController::class, 'removeSettlementBankPurpose'])->whereNumber('id')->whereNumber('purposeId');
            Route::get('', [SettingsController::class, 'getSettlementBank']);
        });

        //cashbacks
        Route::prefix('cashbacks')->group(function () {
            Route::post('', [CashbacksController::class, 'createCashback']);
            Route::get('', [CashbacksController::class, 'getCashbacks']);
            Route::get('trashed', [CashbacksController::class, 'getTrashedCashbacks']);
            Route::get('{id}/untrash', [CashbacksController::class, 'undeleteCashback']);
            Route::get('{id}', [CashbacksController::class, 'getCashback']);
            Route::put('{id}', [CashbacksController::class, 'updateCashback']);
            Route::delete('{id}', [CashbacksController::class, 'deleteCashback']);
        });

        //account settings
        Route::prefix('account-settings')->group(function () {
            Route::post('change-password', [SettingsController::class, 'changePassword']);
        });

        //payment modes
        Route::prefix('payment-modes')->group(function () {
            Route::post('', [PaymentModesController::class, 'addPaymentMode']);
            Route::put('toggle-active/{paymentModeId}', [PaymentModesController::class, 'toggleActive']);
            Route::delete('{paymentModeId}', [PaymentModesController::class, 'removePaymentMethod']);
            Route::get('', [PaymentModesController::class, 'getPaymentModes']);
            Route::get('all', [PaymentModesController::class, 'getAllPaymentModes']);
        });
    });
