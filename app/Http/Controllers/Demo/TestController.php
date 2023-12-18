<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\UserModel;

class TestController extends BaseController {

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

    /**
     * @Desc:删除文件
     * @author: wanf
     * @Time: 2023/12/2 11:24
     */
    public function delFile() {
        $files = [
            'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/uploads/20231130112306_常用人员导入模板.xls',
            'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/uploads/20231130091838_常用人员导入模板.xls',
        ];
        $res = delFiles($files);
        var_dump($res);
    }

    /**
     * @Desc:删除文件和目录
     * @author: wanf
     * @Time: 2023/12/2 11:25
     */
    public function delDirFiles() {
        $files='D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/uploads/a';

        $res = delDirAndFiles($files, true);
        var_dump($res);
    }

    /**
     * @Desc:二维数组排序
     * @author: wanf
     * @Time: 2023/12/2 11:25
     */
    public function arrSort() {
        $sortReg = [10114, 10112, 10113, 10119];

        $data = [
            [
                'seat_id' => 2,
                'user_id' => 10112,
            ],
            [
                'seat_id' => 3,
                'user_id' => 10113,
            ],
            [

                'seat_id' => 4,
                'user_id' => 10114,
            ],
        ];
        echo '<pre>';
        print_r(arraySortByArr($data, $sortReg, 'user_id'));exit;
    }

    public function arraySort() {
        $data = [
            [
                'row_num' => 1,
                'data' => [
                    'name' => 'b',
                    'num' => 1,
                ]
            ],
            [
                'row_num' => 55,
                'data' => [
                    'name' => 'b',
                    'num' => 1,
                ]
            ],
            [
                'row_num' => 3,
                'data' => [
                    'name' => 'b',
                    'num' => 1,
                ]
            ],
        ];
        echo '<pre>';
        print_r(arraySort($data, 'row_num', SORT_ASC));
    }

    public function test() {
        $res = (new UserModel())->singleInsert(['nickname' => 'nickname1', 'username' => '123'], false);
        var_dump($res);
    }

}
