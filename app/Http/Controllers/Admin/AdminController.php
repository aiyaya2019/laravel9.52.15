<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\BusinessLogic\Admin\AdminLogic;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;


class AdminController extends AdminBaseController {


    protected $logic;

    public function __construct() {
        parent::__construct();
        $this->logic = new AdminLogic();
    }

    public function list() {
        try {
            $result = $this->logic->list();

            return returnData(200, '操作成功', $result);

        } catch (Exception $exception) {
            return returnData($exception->getCode(), $exception->getMessage(), [], handleErrorData($exception));
        }
    }



}
