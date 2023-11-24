<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\BusinessLogic\Admin\AdminLogic;
use App\Http\BusinessLogic\Banner\BannerLogic;
use App\Http\Controllers\Api\V1\ApiBaseController;
use Illuminate\Http\Request;

class OpenController extends ApiBaseController {
    protected $adminLogic;

    public function __construct() {
        $this->adminLogic = new AdminLogic();
    }

    public function list() {
        recordLog(1);

        $data = $this->adminLogic->list();
        recordLog(1, $data);

        return returnData(200, __('lang.success'), $data);
    }

    public function add() {
        echo 'add';exit;
    }



}
