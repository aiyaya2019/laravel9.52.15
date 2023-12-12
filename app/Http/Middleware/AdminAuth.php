<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * 后台admin鉴权
 */
class AdminAuth {
    /**
     * 免登陆
     */
    protected $except = ['server/banner/noLogin'];

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

        // if ($this->ipWhitelistCheck($request)) {
        //     return $next($request);
        // }

        if (!Auth::guard('admin')->check()) {
            return response()->json(['code' => 300, 'msg' => '没有登录']);
        }
        return $next($request);
    }

    /**
     * @Desc:ip白名单
     * @param Request $request
     * @return bool
     * @author: wanf
     * @Time: 2023/12/12 14:39
     */
    protected function ipWhitelistCheck(Request $request) {
        $result = false;
        if (in_array($request->getClientIp(), config('jwt.ip_whitelist'))) {
            // // 如果是来自白名单的请求，则默认使用id为1的用户进行鉴权。
            // $user = User::where('id', 1)->first();
            //
            // // 生成 JWT 令牌
            // $token = JWTAuth::fromUser($user);
            //
            // $request->headers->set('Authorization', 'bearer ' . $token);
            // $result = true;
        }

        return $result;
    }
}
