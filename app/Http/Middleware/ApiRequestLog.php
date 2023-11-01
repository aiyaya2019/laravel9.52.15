<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * api请求日志中间件
 */
class ApiRequestLog {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        requestLog('apilog');
        return $next($request);
    }
}
