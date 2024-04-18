<?php

namespace App\Http\Controllers\Admin;

use App\Http\Logic\BannerLogic;
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

        $data = $this->logic->list();
    }

    public function add() {}

    public function edit() {}

    public function delete() {}

    public function noLogin() {
        echo '<pre>';
        echo '白名单，测试跳过登录';
        print_r(ConstantArr::$bannerPos);exit;
    }




}
