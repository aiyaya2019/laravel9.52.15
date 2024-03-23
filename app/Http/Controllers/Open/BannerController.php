<?php

namespace App\Http\Controllers\Open;

use App\Http\Logic\Banner\BannerLogic;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class BannerController extends BaseController {
    protected $logic;

    public function __construct() {
        parent::__construct();
        $this->logic = new BannerLogic();
    }

    public function list() {
        $data = $this->logic->list();
    }




}
