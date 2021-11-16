<?php

use App\Http\Controllers\V1\Collection\CollectionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/collections')->group(function (){
    Route::get('types',[CollectionsController::class,'getCollectionTypes']);
    Route::get('{type}',[CollectionsController::class,'loadCollections']);
    //
    Route::middleware('auth:api')->group(function () {
    });
});
