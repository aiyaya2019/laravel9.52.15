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
        $data = $this->logic->list();
        echo 2;exit;
    }
    public function add() {
        echo 'add';exit;
    }



}
