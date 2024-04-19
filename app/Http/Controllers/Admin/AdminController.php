<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\Logic\AdminLogic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AdminController extends AdminBaseController {

    protected $logic;

    public function __construct() {
        parent::__construct();
        $this->logic = new AdminLogic();
    }

    /**
     * @Desc:列表
     * @return JsonResponse
     * @author: wanf
     * @Time: 2023/11/18 10:19
     */
    public function list(Request $request) {
        try {
            $post = $request->except(['_token', '_method', 's']);

            $result = $this->logic->list($post);

            return returnData(200, __('lang.success'), $result);

        } catch (Exception $exception) {
            return returnData($exception->getCode(), $exception->getMessage(), [], handleErrorData($exception), $exception);
        }
    }

    public function add() {

    }

    public function edit() {}

    public function delete() {}


}
