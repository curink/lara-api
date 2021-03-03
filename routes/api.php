<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthController,
    UserController,
    RoleController,
    SupplierController,
    ResellerController,
    CategoryController
};

Route::group(['prefix' => 'v1'], function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function(){
        Route::get('profile', [AuthController::class, 'show']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('user', UserController::class)->except(['destroy']);
        Route::post('user/set-role/{user}', [UserController::class, 'setRole']);
        Route::apiResources([
            'supplier' => SupplierController::class,
            'reseller' => ResellerController::class,
            'category' => CategoryController::class,
        ]);

        Route::middleware(['role:super-admin'])->group(function(){
            Route::apiResource('role', RoleController::class)->except(['show','update']);
            Route::get('role/permission/{role}', [RoleController::class, 'hasPermission']);
            Route::put('role/permission/{role}', [RoleController::class, 'setRolePermission']);
        });
    });
});
