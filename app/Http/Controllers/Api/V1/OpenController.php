<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\BusinessLogic\Banner\BannerLogic;
use App\Http\Controllers\Api\V1\ApiBaseController;
use Illuminate\Http\Request;

class OpenController extends ApiBaseController {
    protected $logic;

    public function __construct() {
        $this->logic = new BannerLogic();
    }

    public function list() {
        recordLog(1);
        $params = [
            [
                'name' => '姓名',
                'phone' => '13122222222',
            ],
            [
                'name' => '姓名',
                'phone' => '13122222222',
            ],
        ];

        recordLog(1, $params);

        $data = $this->logic->list();

        return returnData(200, __('lang.success'), $data);
    }

    public function add() {
        echo 'add';exit;
    }



}
