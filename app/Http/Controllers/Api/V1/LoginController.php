<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends BaseController {

    function username() {
        return 'account';
    }

    public function guard() {
        return Auth::guard('api');
    }

    /**
     * 登录
     */
    public function login(Request $request) {
        $params = $request->only('account', 'password');

        $user = User::where('account', $params['account'])->first();

        if (!$user) {
            return response()->json(['code' => 400, 'msg' => '用户名或密码错误']);
        }

        if (!Hash::check($params['password'], $user->password)) {
            return response()->json(['code' => 400, 'msg' => '用户名或密码错误']);
        }

        $token = auth('api')->login($user);

        return response()->json(['code' => 200, 'msg' => '登录成功', 'token' => 'Bearer  ' .$token]);

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
