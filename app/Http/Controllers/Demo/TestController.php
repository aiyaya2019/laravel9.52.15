<?php

namespace App\Http\Controllers\Demo;

class TestController {

    /**
     * @Desc:curl请求参数以数组形式发送
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/23 19:37
     */
    public function arrayParamRequest() {
        recordLog(1, 'curl请求 参数以数组形式发送');
        $params = [
            [
                'name' => '姓名',
                'phone' => '13111111111',
            ],
            [
                'name' => '姓名2',
                'phone' => '13122222222',
            ],
        ];

        $url = 'http://laravel9.com:8000/demo/test/receiveCurl';

        recordLog(1);
        recordLog(1, ['format' => 'array', 'url' => $url, 'params' => $params]);

        $res = curlRequest($url, 'post', ['format' => 'array', 'params' => $params]);

        return returnData(200, __('lang.success'), $res);
    }

    /**
     * @Desc:curl请求 参数以json形式发送
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/23 19:37
     */
    public function jsonParamRequest() {
        recordLog(1, 'curl请求 参数以json形式发送');

        $params = [
            'format' => 'json',
            'name' => '姓名',
            'phone' => '13111111111',
        ];

        $url = 'http://laravel9.com:8000/demo/test/receiveCurl';

        // $res = jsonPost($url, $params);
        $res = curlRequest($url, 'post', $params, true);

        return returnData(200, __('lang.success'), $res);
    }

    public function receiveCurl() {
        $data = request()->all();
        return returnData(200, __('lang.success'), $data);
    }

}
