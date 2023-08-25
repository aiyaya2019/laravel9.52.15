<?php

namespace App\Http\Controllers\Admin;

use App\Http\BusinessLogic\Banner\BannerLogic;
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




}
