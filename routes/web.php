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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], 'server/login', [\App\Http\Controllers\Admin\LoginController::class, 'login']);

Route::prefix('/server')->middleware('auth.admin')->group(function () {
    Route::match(['get', 'post'], 'logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout']);

    Route::prefix('/banner')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
    });
});


// Route::prefix('/server')->group(function () {
//     Route::prefix('/banner')->group(function () {
//         Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
//         Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
//     });
//     Route::prefix('/login')->group(function () {
//         Route::match(['get', 'post'], 'login', [\App\Http\Controllers\Admin\LoginController::class, 'login']);
//     });
// });
