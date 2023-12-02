<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## 安装注意事项：

1、项目中没有push vendor的依赖，拉取代码之后先安装依赖：composer install

2、如果没有maatwebsite/excel依赖，就安装3.*以上版本maatwebsite/excel，命令：composer require maatwebsite/excel --update-with-dependencies

3、composer require w7corp/easywechat:^6.7。安装该依赖需要先开启sodium扩展


##登录验证说明：
admin模块登录验证
用admin表验证登录，name,password
password存哈希值，Hash::make(666666);
````
CREATE TABLE `admin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
````

##config/auth.php配置
1、guards加
````
'admin' => [
    'driver' => 'session',
    'provider' => 'admin',
],
````

2、providers加
````
'admin' => [
    'driver' => 'eloquent',
    'model' => App\Models\Admin::class,
],
````

3、php artisan新建路由中间件AdminAuth，路径：Http/Middleware/AdminAuth的handle加验证没登录提示

````
if (!Auth::guard('admin')->check()) {
    return response()->json(['code' => 300, 'msg' => '没有登录']);
}
````

4、在Http/Kernelde $routeMiddleware中加：'auth.admin' => AdminAuth::class,
5、路由中可以使用中间件验证登录
````
Route::prefix('/server')->middleware('auth.admin')->group(function () {
    Route::match(['get', 'post'], 'logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout']);

    Route::prefix('/banner')->group(function () {
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
        Route::match(['get', 'post'], 'list', [\App\Http\Controllers\Admin\BannerController::class, 'list']);
    });
});
````

##token使用：
api模块jwt登录验证
前端请求，header里面加参数Authorization：值为token


##多语言：
使用lang/cn/lang.php 或 lang/en/lang.php的配置。如果英文环境中没有某个给定的字符串翻译时，会默认使用cn的翻译(config/app.php中有配置：'fallback_locale' => 'cn')。

##使用说明
####建议用抛异常，可接收错误详情，如：报错所在文件、第几行、错误信息等

1、使用artisan创建imports类到Http/Excel目录中：php artisan make:import ..\Http\Excel\Imports。创建完成之后更改命名空间

2、上传文件(可单个上传，也可多个同时上传，返回文件路径数组)。保存目录为：D:\phpstudy_pro\WWW\laravel9.52.15\storage\app\public\uploads

