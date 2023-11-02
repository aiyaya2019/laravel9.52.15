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
    // echo json_encode([
    //     'code' => $code,
    //     'msg' => $msg,
    //     'total' => $total,
    //     'data' => $data,
    // ]);exit;

    return response()->json(['code' => $code, 'msg' =>$msg, $total, $data]);
}

/**
 * @Desc:记录请求日志
 * @param int $reqStatus 接口请求状态
 * @param string $useTime 接口用时
 * @param array $returnData 返回数据
 * @param string $model 日志通道
 * @return bool
 * @author: wanf
 * @Time: 2023/11/1 21:41
 */
function requestLog(int $reqStatus, string $useTime = '', array $returnData = [], string $model = 'adminlog') {
    $data = [
        'method' => request()->method(),//请求方式
        'port' => request()->getPort(),//端口
        'ip' => request()->ip(),//ip
        'url' => request()->url(),//url
        'return_code' => $returnData['code'],//返回状态码：200成功，201成功弹出确认窗口，300去登录，400失败
        'return_msg' => $returnData['msg'],//返回信息提示
        'return_data' => env('LOG_RECORD_RETURN_DATA') == 1 ? $returnData : '不记录返回数据',//返回数据
        'params' => request()->all(),//请求参数
        'header' => env('LOG_HEADER_FORMAT') == 1 ? request()->header() : json_encode(request()->header()),
    ];

    if (env('LOG_FORMAT') == 1) {
        Log::channel($model)->info('reqStatus:'. $reqStatus .' useTime:' . $useTime .'ms  ' .print_r($data, true));
    } else {
        Log::channel($model)->info('reqStatus:'. $reqStatus .' useTime:' . $useTime .'ms  ' .json_encode($data));
    }

    return true;
}




