<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 后台请求日志中间件
 */
class AdminRequestLog {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // 记录请求开始时间
        $startTime = microtime(true);

        // 执行下一个中间件或请求处理
        $response = $next($request);

        // 记录请求结束时间
        $endTime = microtime(true);

        $useTime = number_format(($endTime - $startTime) * 1000, 2);

        requestLog($response->getStatusCode(), $useTime, $response->original);

        return $response;
    }
}
