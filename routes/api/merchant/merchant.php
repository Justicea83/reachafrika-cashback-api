<?php

use App\Http\Controllers\V1\Merchant\MerchantsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/merchants')->group(function () {

    Route::middleware('guest')->group(function(){
        Route::post('setup', [MerchantsController::class, 'setup']);
    });

    //authenticated routes
    Route::middleware('auth:api')->group(function(){
        Route::get('', [MerchantsController::class, 'getMerchants']);
        Route::get('{id}', [MerchantsController::class, 'getMerchant']);
        Route::get('by-status/{status}', [MerchantsController::class, 'getMerchantsByStatus']);
        Route::put('{id}', [MerchantsController::class, 'updateMerchant']);
        Route::delete('{id}', [MerchantsController::class, 'deleteMerchant']);
        Route::get('{id}/mark-as-blocked', [MerchantsController::class, 'markAsBlocked']);
        Route::get('{id}/users', [MerchantsController::class, 'getMerchantUsers']);
        Route::post('{id}/users', [MerchantsController::class, 'createMerchantUser']);
        Route::get('{id}/branches', [MerchantsController::class, 'getMerchantBranches']);
        Route::post('{id}/branches', [MerchantsController::class, 'createMerchantBranch']);
        Route::get('{id}/mark-as-pending', [MerchantsController::class, 'markAsPending']);
        Route::get('{id}/mark-as-active', [MerchantsController::class, 'markAsActive']);
    });
});

