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
        $params = [
            'name' => '姓名',
            'phone' => '13111111111',
        ];
        $url = 'http://laravel9.com:8000/demo/test/receiveCurl';

        $res = curlRequest($url, 'post', $params);

        return returnData(200, __('lang.success'), $res);
    }

    /**
     * @Desc:curl请求 参数以json形式发送
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/23 19:37
     */
    public function jsonParamRequest() {
        $params = [
            'name' => '姓名',
            'phone' => '13111111111',
        ];
        $url = 'http://laravel9.com:8000/demo/test/receiveCurl';

        $res = jsonPost($url, $params);

        return returnData(200, __('lang.success'), $res);
    }

    public function receiveCurl() {
        sleep(2);
        $data = request('data');
        return returnData(200, __('lang.success'), $data);
    }

}
