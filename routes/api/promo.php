<?php

use App\Http\Controllers\V1\Promo\PromosController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/promos')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('days', [PromosController::class, 'getDays']);
        Route::get('time', [PromosController::class, 'getTime']);
        Route::get('frequencies', [PromosController::class, 'getFrequencies']);

        Route::prefix('schedules')->group(function () {
            Route::post('', [PromosController::class, 'createSchedule']);
            Route::get('', [PromosController::class, 'getSchedules']);
            Route::put('{id}', [PromosController::class, 'toggleScheduleActive'])->whereNumber('id');
            Route::delete('{id}', [PromosController::class, 'deleteSchedule'])->whereNumber('id');
        });

        Route::prefix('campaigns')->group(function () {
            Route::post('', [PromosController::class, 'createPromoCampaign']);
            Route::get('', [PromosController::class, 'getPromoCampaigns']);
            Route::delete('{id}', [PromosController::class, 'deletePromoCampaign'])->whereNumber('id');
        });
    });
});
