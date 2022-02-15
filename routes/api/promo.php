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
            Route::post('get-impressions-by-budget', [PromosController::class, 'getImpressionsByBudget']);
            Route::get('potential-count', [PromosController::class, 'getPotentialCount']);
            Route::post('target-count', [PromosController::class, 'getTargetCount']);
            Route::get('', [PromosController::class, 'getPromoCampaigns']);
            Route::delete('{id}', [PromosController::class, 'deletePromoCampaign'])->whereNumber('id');
            Route::get('{id}', [PromosController::class, 'getPromoCampaign'])->whereNumber('id');
            Route::get('{id}/pause', [PromosController::class, 'pausePromoCampaign'])->whereNumber('id');
            Route::get('{id}/play', [PromosController::class, 'pausePromoCampaign'])->whereNumber('id');
        });
    });
});
