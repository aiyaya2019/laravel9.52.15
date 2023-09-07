<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth {
    /**
     * 免登陆
     */
    protected $except = ['api/v1/banner/noLogin'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // 免登陆
        if (in_array($request->path(), $this->except)) {
            return $next($request);
        }

        // 跳过登录验证
        if (!empty($request->input('debug')) && $request->input('debug') == 'test') {
            return $next($request);
        }

        if (!Auth::guard('api')->check()) {
            return response()->json(['code' => 300, 'msg' => '没有登录apiauth']);
        }
        return $next($request);
    }
}
