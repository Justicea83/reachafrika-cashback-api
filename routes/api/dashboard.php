<?php

use App\Http\Controllers\V1\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/dashboard')
    ->middleware('auth:api')
    ->group(function (){
    Route::get('overview',[DashboardController::class,'getOverview']);
    Route::get('pos-summary',[DashboardController::class,'posSummary']);
    Route::get('branch-summary',[DashboardController::class,'branchSummary']);

});
