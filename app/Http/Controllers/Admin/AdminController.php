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

    /**
     * @Desc:
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/18 10:19
     */
    public function list() {
        try {
            $result = $this->logic->list();

            return returnData(200, __('lang.success'), $result);

        } catch (Exception $exception) {
            return returnData($exception->getCode(), $exception->getMessage(), [], handleErrorData($exception), $exception);
        }
    }



}
