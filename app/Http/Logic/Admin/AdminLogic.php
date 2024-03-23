<?php

namespace App\Http\Logic\Admin;

use App\Exceptions\HttpMsgException;
use App\Http\Logic\BaseLogic;
use App\Models\AdminModel;

class AdminLogic extends BaseLogic {

    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = new AdminModel();
    }

    /**
     * @Desc:
     * @return array * @throws HttpMsgException
     * @throws HttpMsgException
     * @author: wanf
     * @Time: 2023/12/15 15:12
     */
    public function list() {
        $total = $this->model->getTotal();

        $list = [];
        if ($total) {
            $list = $this->model->getAll();

            if (empty($list)) {
                throw new HttpMsgException('lang.fail');
            }
        }

        return ['total' => $total, 'list' => $list];
    }
}
