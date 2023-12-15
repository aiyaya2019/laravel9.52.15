<?php

namespace App\Http\Controllers\Demo;

use App\Exceptions\HttpMsgException;
use App\Http\Requests\Test2\CheckingRequest;
use Exception;

class Test2Controller {

    /**
     * @Desc:测试参数验证
     * @param CheckingRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @author: wanf
     * @Time: 2023/12/15 15:14
     */
    public function checking(CheckingRequest $request) {
        $params = $request->all();
        echo '<pre>';
        print_r($params);exit;

        try {
            throw new HttpMsgException('lang.fail');

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }
}
