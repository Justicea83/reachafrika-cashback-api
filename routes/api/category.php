<?php

use App\Http\Controllers\V1\Category\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/{type}-categories')
    ->middleware('auth:api')
    ->group(function () {
    Route::post('', [CategoriesController::class, 'create']);
    Route::get('', [CategoriesController::class, 'get']);
    Route::get('groups', [CategoriesController::class, 'getGroups']);
    Route::get('archived', [CategoriesController::class, 'getDeleted']);
    Route::get('undelete/{id}', [CategoriesController::class, 'unDeleteCategory']);
    Route::get('{id}', [CategoriesController::class, 'getCategory']);
    Route::delete('{id}', [CategoriesController::class, 'deleteCategory']);
});
