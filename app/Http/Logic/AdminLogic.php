<?php

namespace App\Http\Logic;

use App\Exceptions\HttpMsgException;
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
    public function list($post = []) {
        $where = [];
        $total = $this->model->getTotal($where);

        $list = [];
        if ($total) {
            $list = $this->model->getList($where, '*', $post['page'], $post['rows']);

            if (empty($list)) {
                throw new HttpMsgException('lang.fail');
            }
        }

        return ['total' => $total, 'list' => $list];
    }

    /**
     * @Desc:添加
     * @param $post
     * @return array * @throws HttpMsgException
     * @throws HttpMsgException
     * @author: wanf
     * @Time: 2024/4/19 16:24
     */
    public function add($post = []) {
        $result = $this->model->singleInsert($post);

        if (empty($result)) {
            throw new HttpMsgException('lang.fail');
        }

        return ['id' => $result];
    }

    /**
     * @Desc:编辑
     * @param $post
     * @return bool * @throws HttpMsgException
     * @throws HttpMsgException
     * @author: wanf
     * @Time: 2024/4/19 16:23
     */
    public function edit($post = []) {
        $id = $post['id'];
        unset($post['id']);

        $checkId = $this->model->getOne(['id' => $id]);

        if (empty($checkId)) {
            throw new HttpMsgException('lang.fail');
        }

        $result = $this->model->singleSave(['id' => $id], $post);

        if (empty($result)) {
            throw new HttpMsgException('lang.fail');
        }

        return true;
    }

    /**
     * @Desc:删除
     * @param $ids
     * @return bool * @throws HttpMsgException
     * @throws HttpMsgException
     * @author: wanf
     * @Time: 2024/4/19 16:23
     */
    public function del($ids) {

        $result = $this->model->singleDelete(['id' => $ids]);

        if (empty($result)) {
            throw new HttpMsgException('lang.fail');
        }

        return true;
    }
}
