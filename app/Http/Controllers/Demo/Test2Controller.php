<?php

namespace App\Http\Controllers\Demo;

use App\Exceptions\HttpMsgException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Test2\CheckingRequest;
use App\Rules\NotEmptyArrayRule;
use App\Rules\ValueExistTableRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Test2Controller extends BaseController {

    /**
     * @Desc:测试参数验证
     * @param CheckingRequest $request
     * @return \Illuminate\Http\JsonResponse|void
     * @author: wanf
     * @Time: 2023/12/15 15:14
     */
    public function checking(CheckingRequest $request) {
        $params = $request->all();
        echo '<pre>';
        print_r($params);exit;

        try {
            throw new HttpMsgException('lang.fail');

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }

    public function checking2(Request $request) {
        $id = request('id', 0);
        $name = request('name', 0);
        $account = request('account', 0);

        // 验证规则
        $rules = [
            'id' => [
                'required',
                'integer:numeric',
                'min:1',
                'exists:admin,id',//验证admin表是否存在该id
            ],

            'type' => [
                'required',
                'integer:numeric',
                Rule::in([1, 2, 3]),
            ],

            'role_ids' => [
                'array',
                new NotEmptyArrayRule(),//数组不能包含false, 0, null等空值
            ],
            'role_ids.*' => 'integer|distinct',//数组中的值必须为整数|数组中的值不能重复

            'relation' => ['array'],
            'relation.*.id' => 'integer|distinct',//数组中id的值必须为整数|数组中id的值不能重复

            'pos_ids' => [
                'array',
                new ValueExistTableRule('admin', 'id'),//验证admin表是否存在对应id值
            ],

            'account' => [
                Rule::unique('admin')->ignore(1, 'deleted'),
            ],

            'name' => [
                Rule::unique('admin')->where(function ($query) use($id, $name) {
                    $query->where('id', '<>', $id)->where('deleted', 0);
                })
            ],
        ];

        // 自定义错误消息
        $msg = [
            'id.required' => sprintf(__('lang.param_required'), 'id'),
            'id.integer' => sprintf(__('lang.param_int'), 'id'),
            'id.min' => sprintf(__('lang.param_min1'), 'id'),
            'id.exists' => __('lang.param_error'),

            'type.required' => sprintf(__('lang.param_required'), 'type'),
            'type.integer' => sprintf(__('lang.param_int'), 'type'),
            'type.in' => __('lang.param_error'),

            'role_ids.array' => sprintf(__('lang.param_array'), 'role_ids'),
            'role_ids.*.integer' => sprintf(__('lang.param_array_int'), 'role_ids'),
            'role_ids.*.distinct' => sprintf(__('lang.param_array_unique'), 'role_ids'),

            'relation.array' => sprintf(__('lang.param_array'), 'relation'),
            'relation.*.id.integer' => sprintf(__('lang.param_array_val_int'), 'relation', 'id'),//relation是二维数组
            'relation.*.id.distinct' => sprintf(__('lang.param_array_val_unique'), 'relation', 'id'),

            'pos_ids.array' => sprintf(__('lang.param_array'), 'pos_ids'),

            'account.unique' => 'account 已存在',
            'name.unique' => 'name 已存在',
        ];

        // 执行验证
        $errorMsg = $this->validation($request->all(), $rules, $msg);

        // 检查验证是否通过
        if ($errorMsg) {
            // 如果验证失败，返回错误信息
            return returnData(400, $errorMsg);
        }

        return returnData();
    }
}
