<?php

use App\Http\Controllers\V1\UserManagement\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user-mgmt')->group(function (){
    Route::middleware('auth:api')->group(function () {
        Route::get('assign-role-by-name/{userId}/{name}',[UserManagementController::class,'assignRoleToUserByRoleName']);
        Route::get('assign-permission-by-name/{userId}/{name}',[UserManagementController::class,'assignPermissionToUser']);
        Route::get('assign-role-by-id/{userId}/{id}',[UserManagementController::class,'assignRoleToUserByRoleId']);
        Route::get('get-user-roles',[UserManagementController::class,'getUserRoles']);
        Route::get('get-user-permissions',[UserManagementController::class,'getUserPermissions']);
        Route::get('all-permissions',[UserManagementController::class,'getAllPermissions']);
        Route::post('assign-permissions-to-roles',[UserManagementController::class, 'assignPermissionsToRole']);
        //users

        Route::prefix('users')->group(function (){
            Route::post('',[UserManagementController::class,'createUser']);
            Route::get('',[UserManagementController::class,'getUsers']);
            Route::post('suspend/{id}',[UserManagementController::class,'suspendUser']);
            Route::get('{id}',[UserManagementController::class,'getUserById']);
            Route::put('{id}',[UserManagementController::class,'updateUser']);
        });

        //roles
        Route::prefix('roles')->group(function (){
            Route::post('',[UserManagementController::class,'createRole']);
            Route::get('',[UserManagementController::class,'getRoles']);
            Route::get('for-user/{userId}',[UserManagementController::class,'getRolesForUser']);
            Route::delete('{id}',[UserManagementController::class,'deleteRole']);
            Route::put('{id}',[UserManagementController::class,'updateRole']);
        });
    });
});
