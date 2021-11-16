<?php

use App\Http\Controllers\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function (){
    Route::post('forgot-password',[AuthController::class,'forgotPassword']);
    Route::post('reset-password',[AuthController::class,'resetPassword']);
    //
    Route::middleware('auth:api')->group(function () {
        Route::post('mobile-logout',[AuthController::class,'mobileLogout']);
        Route::get('me',[AuthController::class,'getAuthenticatedUser']);
        Route::post('logout-of-all-devices',[AuthController::class,'logoutOfAllDevices']);
    });
});
