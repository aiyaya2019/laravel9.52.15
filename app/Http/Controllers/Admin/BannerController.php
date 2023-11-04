<?php

namespace App\Http\Controllers\Admin;

use App\Http\BusinessLogic\Banner\BannerLogic;
use App\Http\Common\Constant;
use App\Http\Common\ConstantArr;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerController extends AdminBaseController {
    protected $logic;

    public function __construct() {
        parent::__construct();
        $this->logic = new BannerLogic();
    }

    public function list() {
        print_r(ConstantArr::$bannerPos);exit;
        // returnData(200, '操作成功', 5, $data = [1,2,3,4,5]);
        // return response()->json(['code' => 300, 'msg' => '1234', 'total'=>3, 'data' => [1,2,3]]);
        // echo 2;exit;
        $data = $this->logic->list();
    }

    public function noLogin() {
        echo '白名单，测试跳过登录';
    }




}
