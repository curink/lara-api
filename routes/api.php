<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ {
    AuthController,
    UserController,
    RoleController
};

Route::group(['prefix' => 'v1'], function() {
    Route::middleware(['guest'])->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:api'])->group(function() {
        Route::get('profile', [AuthController::class, 'show']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        Route::middleware(['verified'])->group(function() {

            Route::apiResource('user', UserController::class)->except(['destroy']);
    
            Route::middleware(['role:super-admin'])->group(function() {
                Route::post('user/set-role/{user}', [UserController::class, 'setRole']);
                Route::apiResource('role', RoleController::class)->except(['show', 'update']);
                Route::get('/permission', [RoleController::class, 'permission']);
                Route::get('role/permission/{role}', [RoleController::class, 'hasPermission']);
                Route::put('role/permission/{role}', [RoleController::class, 'setRolePermission']);
            });
            
            /*Route::apiResources([
                //
            ]);*/
        });
    });
});