<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\RoleController;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\ModuleController;
use App\Http\Controllers\v1\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::controller(ModuleController::class)->prefix('module')->group(function () {
        Route::get('/', 'list');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('update/{id}', 'update');
        // Route::delete('delete/{id}', 'delete');
        Route::post('softDelete/{id}', 'softDelete');
        Route::get('restore/{id}', 'restore');
        Route::get('restoreAll', 'restoreAll');
    });

    Route::controller(PermissionController::class)->prefix('permission')->group(function () {
        Route::get('/', 'list');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('update/{id}', 'update');
        // Route::delete('delete/{id}', 'delete');
        Route::post('softDelete/{id}', 'softDelete');
        Route::get('restore/{id}', 'restore');
        Route::get('restoreAll', 'restoreAll');
    });

    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::get('/', 'list');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('update/{id}', 'update');
        // Route::delete('delete/{id}', 'delete');
        Route::post('softDelete/{id}', 'softDelete');
        Route::get('restore/{id}', 'restore');
        Route::get('restoreAll', 'restoreAll');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/', 'list');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('update/{id}', 'update');
        // Route::delete('delete/{id}', 'delete');
        Route::post('softDelete/{id}', 'softDelete');
        Route::get('restore/{id}', 'restore');
        Route::get('restoreAll', 'restoreAll');
    });
});
