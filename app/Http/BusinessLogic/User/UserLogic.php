<?php

namespace App\Http\BusinessLogic\User;

use App\Http\BusinessLogic\BaseLogic;
use App\Models\UserModel;
use Illuminate\Support\Facades\Cache;

class UserLogic extends BaseLogic {

    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = new UserModel();
    }

    /**
     * @Desc:小程序保存用户信息
     * @param array $params
     * @return array|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|object|null * @throws \ErrorException
     * @author: wanf
     * @Time: 2023/12/11 19:23
     */
    public function saveUserInfo(array $params) {
        $openid = $params['openid'];

        $user = $this->model->getOne(['openid' => $openid]);
        if ($user) {
            unset($params['openid']);
            $result = $this->model->singleSave(['openid' => $openid], $params);
        } else {
            $result = $this->model->singleInsert($params);
        }

        if (empty($result)) {
            throw new \ErrorException(__('lang.error'), 400);
        }

        $user = $this->model->getOne(['openid' => $openid]);

        // Cache::put($openid, $user);
        // print_r(Cache::get($openid));

        return $user;
    }

    /**
     * @Desc:获取用户信息
     * @return array|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|object * @throws \ErrorException
     * @author: wanf
     * @Time: 2023/12/11 19:23
     */
    public function getUserInfo() {
        $user = $this->model->getOne(['id' => $this->user['id']]);
        if (empty($user)) {
            throw new \ErrorException(__('lang.error'), 400);
        }

        return $user;
    }
}
