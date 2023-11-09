<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['admin.request.log'])->get('/', function () {
    return view('welcome');
});

Route::middleware(['admin.request.log'])->match(['get', 'post'], 'server/login', [\App\Http\Controllers\Admin\LoginController::class, 'login']);

// 后台需要校验权限的接口
Route::prefix('/server')->middleware(['admin.request.log', 'auth.admin'])->group(function () {
    Route::match(['get', 'post'], 'logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout']);

    Route::prefix('/user')->group(function () {
        Route::match(['get', 'post'], 'export', [\App\Http\Controllers\Admin\UserController::class, 'export']);
    });

    Route::prefix('/banner')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
        Route::match(['get', 'post'], 'noLogin', [\App\Http\Controllers\Admin\BannerController::class, 'noLogin']);
    });
});

// 不需校验权限的接口
Route::prefix('/open')->middleware(['admin.request.log'])->group(function () {
    Route::prefix('/banner')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Open\BannerController::class, 'list']);
    });
    Route::prefix('/test')->group(function () {
        Route::match(['get', 'post'], 't1', [\App\Http\Controllers\Open\TestController::class, 't1']);
    });
});
