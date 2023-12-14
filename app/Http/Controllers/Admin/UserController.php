<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\BusinessLogic\User\UserLogic;

class UserController extends AdminBaseController {
    protected $logic;

    public function __construct() {
        parent::__construct();

        $this->logic = new UserLogic();
    }

    public function list() {
        try {
            $result = $this->logic->list();

            return returnData(200, __('lang.success'), $result);

        } catch (Exception $exception) {
            return returnData($exception->getCode(), $exception->getMessage(), [], handleErrorData($exception), $exception);
        }
    }

    public function add() {}

    public function edit() {}

    public function delete() {}

}
