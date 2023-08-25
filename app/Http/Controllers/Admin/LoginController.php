<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController {

    function username() {
        return 'name';
    }

    public function guard() {
        return Auth::guard('admin');
    }

    /**
     * 登录
     */
    public function login(Request $request) {
        $params = $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|min:5',
          ]);
        //   $params = $request->only('name', 'password');

        // 数据表密码需要转哈希存储，如Hash::make(666666);
        if(Auth::guard('admin')->attempt($params)){
            // $adminInfo = Auth::guard('admin')->user();
            // $adminInfo = json_decode(json_encode($adminInfo), true);
            // print_r($adminInfo);

            return response()->json(['code' => 200, 'msg' => '登录成功']);
        } else {
            return response()->json(['code' => 403, 'msg' => '用户名或密码错误']);
        }
    }

    /**
     * 退出登录
     */
    public function logout() {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['code' => 300, 'msg' => '没有登录']);
        }

        Auth::guard('admin')->logout();

        return response()->json(['code' => 200, 'msg' => '退出成功']);
    }

}
