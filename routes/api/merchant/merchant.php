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
        Route::get('me', [MerchantsController::class, 'getMyMerchant']);
        Route::get('{id}', [MerchantsController::class, 'getMerchant'])->whereNumber('id');
        Route::get('by-status/{status}', [MerchantsController::class, 'getMerchantsByStatus']);
        Route::put('{id}', [MerchantsController::class, 'updateMerchant'])->whereNumber('id');
        Route::delete('', [MerchantsController::class, 'deleteMerchant']);
        Route::get('mark-as-blocked', [MerchantsController::class, 'markAsBlocked']);
        //users
        Route::get('{id}/users', [MerchantsController::class, 'getMerchantUsers'])->whereNumber('id');
        Route::post('{id}/users', [MerchantsController::class, 'createMerchantUser'])->whereNumber('id');
        //branches
        Route::get('branches', [MerchantsController::class, 'getMerchantBranches']);
        Route::delete('branches/{id}', [MerchantsController::class, 'deleteBranch'])->whereNumber('id');
        Route::put('branches/{id}/{status}', [MerchantsController::class, 'changeBranchStatus'])
            ->whereNumber('id')->whereAlpha('status')->where('status','(active|pending|blocked)');
        Route::post('branches', [MerchantsController::class, 'createMerchantBranch']);

        Route::get('mark-as-pending', [MerchantsController::class, 'markAsPending']);
        Route::get('mark-as-active', [MerchantsController::class, 'markAsActive']);
    });
});

