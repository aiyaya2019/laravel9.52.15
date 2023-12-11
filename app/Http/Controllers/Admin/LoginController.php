<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController {

    function username() {
        return 'account';
    }

    public function guard() {
        return Auth::guard('admin');
    }

    /**
     * 登录
     */
    public function login(Request $request) {
        $params = $this->validate($request, [
            'account' => 'required|max:50',
            'password' => 'required|min:5',
          ]);
        //   $params = $request->only('account', 'password');

        // 数据表密码需要转哈希存储，如Hash::make(666666);
        if(Auth::guard('admin')->attempt($params)){
            // $adminInfo = Auth::guard('admin')->user();
            // $adminInfo = json_decode(json_encode($adminInfo), true);
            // print_r($adminInfo);

            return response()->json(['code' => 200, 'msg' => __('lang.login_success')]);
        } else {
            return response()->json(['code' => 403, 'msg' => __('lang.account_pwd_error')]);
        }
    }

    /**
     * 退出登录
     */
    public function logout() {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['code' => 300, 'msg' => __('lang.error')]);
        }

        Auth::guard('admin')->logout();

        return response()->json(['code' => 200, 'msg' => __('lang.success')]);
    }

}
