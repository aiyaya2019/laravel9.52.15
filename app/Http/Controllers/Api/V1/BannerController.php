<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\BusinessLogic\Banner\BannerLogic;
use App\Http\Controllers\Api\V1\ApiBaseController;
use Illuminate\Http\Request;

class BannerController extends ApiBaseController {
    protected $logic;

    public function __construct() {
        $this->logic = new BannerLogic();
    }

    public function list() {
        $data = $this->logic->list();
    }

    public function noLogin() {
        returnData(200, '白名单，测试跳过登录');
    }



}
