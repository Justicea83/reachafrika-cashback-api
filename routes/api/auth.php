<?php

use App\Http\Controllers\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function (){
    Route::post('forgot-password',[AuthController::class,'forgotPassword']);
    Route::post('reset-password',[AuthController::class,'resetPassword']);
    Route::post('login',[AuthController::class,'mobileLogin']);
    Route::post('login-with-refresh-token',[AuthController::class,'refreshToken']);
    //
    Route::middleware('auth:api')->group(function () {
        Route::post('logout',[AuthController::class,'mobileLogout']);
        Route::get('me',[AuthController::class,'getAuthenticatedUser']);
        Route::post('logout-of-all-devices',[AuthController::class,'logoutOfAllDevices']);
    });
});
