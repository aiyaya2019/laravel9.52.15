<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController {

    function username() {
        return 'name';
    }

    public function guard() {
        return Auth::guard('api');
    }

    /**
     * 登录
     */
    public function login(Request $request) {
        $params = $request->only('name', 'password');

        // 数据表密码需要转哈希存储，如Hash::make(666666);
        if($token = Auth::guard('api')->attempt($params)){
            $userInfo = Auth::guard('api')->user();

            return response()->json(['code' => 200, 'msg' => '登录成功', 'token' => $userInfo->remember_token]);
        } else {
            return response()->json(['code' => 400, 'msg' => '用户名或密码错误']);
        }
    }

    /**
     * 退出登录
     */
    public function logout() {
        if (!Auth::guard('api')->check()) {
            return response()->json(['code' => 300, 'msg' => '没有登录']);
        }

        Auth::guard('api')->logout();

        return response()->json(['code' => 200, 'msg' => '退出成功']);
    }

}
