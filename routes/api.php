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


Route::middleware(['api.request.log'])->match(['get', 'post'], '/v1/login/login', [\App\Http\Controllers\Api\V1\LoginController::class, 'login']);//api接口登录
Route::middleware(['api.request.log'])->match(['post'], '/v1/wechat/login', [\App\Http\Controllers\Api\V1\WeChatController::class, 'login']);//微信登录

Route::prefix('/v1')->middleware(['api.request.log', 'auth.api'])->group(function () {
    Route::match(['post'], 'login/logout', [\App\Http\Controllers\Api\V1\LoginController::class, 'logout']);

    Route::prefix('wechat')->group(function () {
        Route::match(['post'], 'getPhone', [\App\Http\Controllers\Api\V1\WeChatController::class, 'getPhone']);
        Route::match(['post'], 'getMiniCode', [\App\Http\Controllers\Api\V1\WeChatController::class, 'getMiniCode']);
    });
    Route::prefix('me')->group(function () {
        Route::match(['post'], 'saveUserInfo', [\App\Http\Controllers\Api\V1\MeController::class, 'saveUserInfo']);
        Route::match(['post'], 'getUserInfo', [\App\Http\Controllers\Api\V1\MeController::class, 'getUserInfo']);
    });

    Route::prefix('banner')->group(function () {
        Route::match(['post'], 'list', [\App\Http\Controllers\Api\V1\BannerController::class, 'list']);
        Route::match(['post'], 'noLogin', [\App\Http\Controllers\Api\V1\BannerController::class, 'noLogin']);
    });
});

Route::prefix('/v1')->middleware(['api.request.log'])->group(function () {
    Route::prefix('open')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Api\V1\OpenController::class, 'list']);
        Route::match(['get', 'post'], 'add', [\App\Http\Controllers\Api\V1\OpenController::class, 'add']);
    });
});
