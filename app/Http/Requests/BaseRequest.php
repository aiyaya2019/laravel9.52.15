<?php

namespace App\Http\Requests;

use App\Exceptions\ValidatorException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 基础表单验证类
 */
class BaseRequest extends FormRequest {
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
        return [];
    }

    /**
     * @Desc:返回自定义的错误信息
     * @return array
     * @author: wanf
     * @Time: 2023/12/14 19:20
     */
    public function messages() {
        return [];
    }

    public function failedValidation(Validator $validator) {

        throw new ValidatorException($validator->errors()->first());

        // throw new HttpResponseException(response()->json([
        //     'errors' => $validator->errors()->first(),
        // ], 400));
    }
}
