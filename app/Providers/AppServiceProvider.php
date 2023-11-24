<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // Schema::defaultStringLength(191);//解决迁移报错PDOException::("SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes")

        // 监听数据库查询事件，但默认情况下，它只能监听查询的执行情况，无法直接捕获异常的 SQL 语句
        DB::listen(
            function ($query) {
                $model = str_contains(request()->url(), '/api/') ? 'apisql' : 'adminsql';

                $tmp = str_replace('?', '"' . '%s' . '"', $query->sql);
                $bindings = [];
                foreach ($query->bindings as $key => $value) {
                    if (is_numeric($key)) {
                        $bindings[] = $value;
                    } else {
                        $tmp = str_replace(':' . $key, '"' . $value . '"', $tmp);
                    }
                }
                $tmp = vsprintf($tmp, $bindings);
                $tmp = str_replace('\\', '', $tmp);
                Log::channel($model)->info('execution time: ' . $query->time . 'ms; ' . $tmp);
            }
        );
    }
}
