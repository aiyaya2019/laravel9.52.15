<?php

namespace App\Http\Requests\Test2;

use App\Http\Requests\BaseRequest;
use App\Rules\NotEmptyArrayRule;
use App\Rules\ValueExistTableRule;
use Illuminate\Validation\Rule;

/**
 * 测试验证器
 */
class CheckingRequest extends BaseRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {

        $id = request('id', 0);
        $name = request('name', 0);

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
                Rule::unique('admin')->where('id', $id)->ignore(1, 'deleted'),
            ],

            'name' => [
                Rule::unique('admin')->where(function ($query) use($id, $name) {
                    $query->where('name', $name)->where('id', '<>', $id)->where('deleted', 0);
                })
            ],
        ];

        return array_merge(parent::rules(), $rules);
    }

    /**
     * @Desc:获取被定义验证规则的错误消息
     * @return array
     * @author: wanf
     * @Time: 2023/12/14 19:21
     */
    public function messages() {
        $messages = [
            'id.required' => sprintf(__('lang.param_required'), 'id'),
            'id.integer' => sprintf(__('lang.param_int'), 'id'),
            'id.min' => sprintf(__('lang.param_min1'), 'id'),
            'id.exists' => 'lang.param_error',

            'type.required' => sprintf(__('lang.param_required'), 'type'),
            'type.integer' => sprintf(__('lang.param_int'), 'type'),
            'type.in' => 'lang.param_error',

            'role_ids.array' => sprintf(__('lang.param_array'), 'role_ids'),
            'role_ids.*.integer' => sprintf(__('lang.param_array_int'), 'role_ids'),
            'role_ids.*.distinct' => sprintf(__('lang.param_array_unique'), 'role_ids'),

            'relation.array' => sprintf(__('lang.param_array'), 'relation'),
            'relation.*.id.integer' => sprintf(__('lang.param_array_val_int'), 'relation', 'id'),//relation是二维数组
            'relation.*.id.distinct' => sprintf(__('lang.param_array_val_unique'), 'relation', 'id'),

            'pos_ids.array' => sprintf(__('lang.param_array'), 'pos_ids'),

            'account.unique' => 'account已存在',
            'name.unique' => 'name已存在',
        ];

        return array_merge(parent::messages(), $messages);
    }
}
