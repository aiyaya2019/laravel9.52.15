<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::match(['get', 'post'], '/v1/login/login', [\App\Http\Controllers\Api\V1\LoginController::class, 'login']);

Route::prefix('/v1')->middleware(['auth.api'])->group(function () {
    Route::match(['get', 'post'], 'login/logout', [\App\Http\Controllers\Api\V1\LoginController::class, 'logout']);

    Route::prefix('banner')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Api\V1\BannerController::class, 'list']);
        Route::match(['get', 'post'], 'noLogin', [\App\Http\Controllers\Api\V1\BannerController::class, 'noLogin']);
    });
});

Route::prefix('/v1')->group(function () {
    Route::prefix('open')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Api\V1\OpenController::class, 'list']);
        Route::match(['get', 'post'], 'add', [\App\Http\Controllers\Api\V1\OpenController::class, 'add']);
    });
});
