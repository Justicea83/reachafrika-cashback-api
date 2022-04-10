<?php

use App\Http\Controllers\V1\Collection\CollectionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/collections')
    ->group(function () {
        Route::get('{type}/{typeId}', [CollectionsController::class, 'loadCollectionsLevel2'])->whereNumber('typeId');
        Route::get('types', [CollectionsController::class, 'getCollectionTypes']);
        Route::get('{type}', [CollectionsController::class, 'loadCollections']);
        //
        Route::middleware('auth:api')->group(function () {
            Route::get('auth/types', [CollectionsController::class, 'getCollectionTypesAuth']);
            Route::get('auth/{type}', [CollectionsController::class, 'loadCollectionsAuth']);
        });
    });
