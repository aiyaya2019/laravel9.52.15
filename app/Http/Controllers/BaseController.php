<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {}

    /**
     * @Desc:验证器
     * @param array $params 待验证的参数
     * @param array $rules 验证规则
     * @param array $msg 错误消息集
     * @return mixed|string 错误消息
     * @author: wanf
     * @Time: 2023/12/18 10:55
     */
    public function validation(array $params, array $rules, array $msg) {

        $error = '';

        // 执行验证
        $validator = Validator::make($params, $rules, $msg);

        // 检查验证是否通过
        if ($validator->fails()) {
            $errorMsg = $validator->errors()->getMessages();
            $keys = array_keys($errorMsg);

            // 如果验证失败，返回错误信息
            $error = $errorMsg[$keys[0]][0];
        }

        return $error;
    }



}
