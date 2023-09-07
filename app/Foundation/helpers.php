<?php

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




