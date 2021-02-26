<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthController,
    UserController,
    RoleController
};

Route::group(['prefix' => 'v1'], function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function(){
        Route::post('logout', [AuthController::class, 'logout']);

        Route::put('user-update', [UserController::class, 'updateAuth']);
        Route::put('user-update/password/{user}', [UserController::class, 'updatePassword']);
        Route::put('user-update/password', [UserController::class, 'updatePasswordAuth']);
        Route::post('user/set-role/{user}', [UserController::class, 'setRole']);
        Route::apiResources([
            'user' => UserController::class,
        ]);

        Route::middleware(['role:super-admin'])->group(function(){
            Route::apiResource('role', RoleController::class)->except(['show','update']);
            Route::post('role/permission', [RoleController::class, 'hasPermission']);
            Route::put('role/permission/{role}', [RoleController::class, 'setRolePermission']);
        });
    });
});
