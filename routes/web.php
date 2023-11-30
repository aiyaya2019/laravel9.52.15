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

// 登录
Route::middleware(['admin.request.log'])->match(['get', 'post'], 'server/login', [\App\Http\Controllers\Admin\LoginController::class, 'login']);

// 后台需要校验权限的接口
Route::prefix('/server')->middleware(['admin.request.log', 'auth.admin'])->group(function () {
    Route::match(['get', 'post'], 'logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout']);

    Route::prefix('/admin')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\AdminController::class, 'list']);
    });

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

// 对外开放的公共函数接口
Route::prefix('/common')->middleware(['admin.request.log'])->group(function () {
    Route::match(['get', 'post'], 'uploadFile', [\App\Http\Controllers\CommonController::class , 'uploadFile']);
});

//用例路由入口
Route::prefix('demo')->group(function () {
    // Demo\ExportController控制器路由入口
    Route::match(['get', 'post'],'export/{action}', function(App\Http\Controllers\Demo\ExportController $index, $action){
        return $index->$action();
    });

    // Demo\ImportController控制器路由入口
    Route::prefix('/import')->group(function () {
        Route::match(['get', 'post'], 'usePublicImport', [\App\Http\Controllers\Demo\ImportController::class, 'usePublicImport']);
        Route::match(['get', 'post'], 'useCollectionImport', [\App\Http\Controllers\Demo\ImportController::class, 'useCollectionImport']);
    });
});

// Demo\TestController控制器路由入口 调试用
Route::middleware(['admin.request.log'])->match(['get', 'post'],'demo/test/{action}', function(App\Http\Controllers\Demo\TestController $index, $action){
    return $index->$action();
});
