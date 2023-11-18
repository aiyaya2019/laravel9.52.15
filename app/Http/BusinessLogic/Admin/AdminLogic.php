<?php

namespace App\Http\BusinessLogic\Admin;

use App\Http\BusinessLogic\BaseLogic;
use App\Models\AdminModel;

class AdminLogic extends BaseLogic {

    protected $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    /**
     * @Desc:
     * @return array * @throws \ErrorException
     * @throws \ErrorException
     * @author: wanf
     * @Time: 2023/11/18 9:49
     */
    public function list() {

        $total = $this->model->getTotal();

        $list = [];
        if ($total) {
            $list = $this->model->getAll();

            if (empty($list)) {
                throw new \ErrorException('暂无数据', 400);
            }
        }

        return ['total' => $total, 'list' => $list];
    }
}
