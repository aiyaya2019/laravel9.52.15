<?php

use Illuminate\Support\Facades\Log;

/**
 * 对象转数组
 * @param object $object
 */
function objectToArray($object) {
    //先编码成json字符串，再解码成数组
    return !empty($object) ? json_decode(json_encode($object), true) : [];
}

/**
 * 返回数据给前端
 * code 状态码：200成功，201成功弹出确认窗口，300去登录，400失败
 * msg 提示信息
 * total 数据总数
 * data 数据
 * @return array
 */
function returnData($code = 200, $msg = '操作成功', $total = 0, $data = []) {
    echo json_encode([
        'code' => $code,
        'msg' => $msg,
        'total' => $total,
        'data' => $data,
    ]);exit;
}

/**
 * @Desc:记录请求日志
 * @param string $model 日志通道
 * @return bool
 * @Time: 2023/11/1 20:53
 */
function requestLog(string $model) {

    $data = [
        'method' => request()->method(),//请求方式
        'port' => request()->getPort(),//端口
        'ip' => request()->ip(),//ip
        'url' => request()->url(),//url
        'params' => request()->all(),//请求参数
        'header' => request()->header(),
    ];

    if (env('LOG_FORMAT') == 1) {
        Log::channel($model)->info(print_r($data, true));
    } else {
        Log::channel($model)->info(json_encode($data));
    }

    return true;
}




