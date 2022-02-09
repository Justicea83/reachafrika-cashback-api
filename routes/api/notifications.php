<?php


use App\Http\Controllers\V1\Notifications\NotificationsController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/notifications')
    ->middleware('auth:api')
    ->group(function () {
        Route::prefix('fcm')->group(function () {
            Route::post('register', [NotificationsController::class, 'registerForFcmNotifications']);
        });
    });
