<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Http\Logic\User\UserLogic;

class MeController extends ApiBaseController {
    public $logic;

    public function __construct() {
        parent::__construct();
        $this->logic = new UserLogic();
    }

    /**
     * @Desc:获取用户信息
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/12/11 19:24
     */
    public function getUserInfo() {
        try {
            $result = $this->logic->getUserInfo();

            return returnData(200, __('lang.success'), $result);

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }

    /**
     * @Desc:保存用户信息
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/12/11 19:24
     */
    public function saveUserInfo() {
        $params = [
            'openid' => $this->user['openid'],
            'nickname' => request('nickname', ''),//昵称
            'avatar' => request('avatar', ''),//头像
            'province' => request('province', ''),//省
            'city' => request('city', ''),//市
        ];

        try {
            $result = $this->logic->saveUserInfo($params);

            return returnData(200, __('lang.success'), $result);
        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }

}
