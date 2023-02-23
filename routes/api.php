<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
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
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::controller(UserController::class)->prefix('user')->group(function () {
            Route::post('/', 'list')->middleware('access:setting,view_access');
            Route::post('create', 'create')->middleware('access:setting,add_access');
            Route::get('show/{id}', 'show')->middleware('access:setting,view_access');
            Route::post('update/{id}', 'update')->middleware('access:setting,edit_access');
            Route::post('softDelete/{id}', 'softDelete')->middleware('access:setting,delete_access');
            Route::get('restore/{id}', 'restore');
        });

        Route::controller(RoleController::class)->prefix('role')->group(function () {
            Route::post('/', 'list')->middleware('access:setting,view_access');
            Route::post('create', 'create')->middleware('access:setting,add_access');
            Route::get('show/{id}', 'show')->middleware('access:setting,view_access');
            Route::post('update/{id}', 'update')->middleware('access:setting,edit_access');
            Route::post('softDelete/{id}', 'softDelete')->middleware('access:setting,delete_access');
            Route::get('restore/{id}', 'restore');
        });

        Route::controller(PermissionController::class)->prefix('permission')->group(function () {
            Route::post('/', 'list')->middleware('access:setting,view_access');
            Route::post('create', 'create')->middleware('access:setting,add_access');
            Route::get('show/{id}', 'show')->middleware('access:setting,view_access');
            Route::post('update/{id}', 'update')->middleware('access:setting,edit_access');
            Route::post('softDelete/{id}', 'softDelete')->middleware('access:setting,delete_access');
            Route::get('restore/{id}', 'restore');
        });

        Route::controller(ModuleController::class)->prefix('module')->group(function () {
            Route::post('/', 'list')->middleware('access:setting,view_access');
            Route::post('create', 'create')->middleware('access:setting,add_access');
            Route::get('show/{id}', 'show')->middleware('access:setting,view_access');
            Route::post('update/{id}', 'update')->middleware('access:setting,edit_access');
            Route::post('softDelete/{id}', 'softDelete')->middleware('access:setting,delete_access');
            Route::get('restore/{id}', 'restore');
        });
    });
});
