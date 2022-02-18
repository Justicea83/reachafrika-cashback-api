<?php

use App\Http\Controllers\V1\Merchant\PosController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/pos')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::post('', [PosController::class, 'createBranchPos']);
        Route::get('', [PosController::class, 'getAllPos']);
        Route::get('{id}/undelete', [PosController::class, 'undeletePos']);
        Route::get('archived', [PosController::class, 'getArchivedPos']);
        Route::get('get-mobile-app-dashboard-stats', [PosController::class, 'getMobileAppDashboardStats']);
        Route::get('{id}', [PosController::class, 'getPos'])->whereNumber('id');
        Route::get('get-mobile-app-dashboard-stats', [PosController::class, 'getMobileAppDashboardStats']);
        Route::get('generate-qr-code/{id}', [PosController::class, 'generateQrCode']);
        Route::get('by-branch/{id}', [PosController::class, 'getBranchPos']);
        Route::get('by-merchant/{id}', [PosController::class, 'getMerchantPos']);
        //Route::get('by-status/{status}', [MerchantsController::class, 'getMerchantsByStatus']);
        Route::put('{id}', [PosController::class, 'updateBranchPos']);
        Route::delete('{id}', [PosController::class, 'deletePos']);
        Route::get('{id}/mark-as-blocked', [PosController::class, 'markAsBlocked']);
        Route::post('{id}/assign-to-user', [PosController::class, 'assignPosToUser']);
        Route::post('{id}/assign-to-branch', [PosController::class, 'assignPosToBranch']);
        Route::get('{id}/mark-as-pending', [PosController::class, 'markAsPending']);
        Route::get('{id}/mark-as-active', [PosController::class, 'markAsActive']);

        Route::prefix('approval-requests')->group(function () {
            Route::post('', [PosController::class, 'sendApprovalRequest']);
            Route::get('me', [PosController::class, 'getMyApprovals']);
            Route::post('me/action', [PosController::class, 'approvalRequestActionCall']);
        });
        //pos routes
    });
});
