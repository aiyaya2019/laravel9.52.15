<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Common\ConstantArr;
use Illuminate\Http\Request;

/**
 * 可对外暴露的公共函数
 */
class CommonController extends BaseController {

    /**
     * @Desc:上传文件，可单文件上传，也可多文件一起上传，返回文件路径数组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/30 17:17
     */
    public function uploadFile(Request $request) {
        // 获取上传的文件
        $files = $request->file('file');

        try {
            $data = [];

            if (is_array($files)) {
                // 多文件上传
                foreach ($files as $file) {
                    // 验证上传的文件
                    $extension = $file->getClientOriginalExtension();//文件扩展名
                    if (!in_array($extension, ConstantArr::$uploadExtension)) {
                        throw new \ErrorException(__('lang.file_suffix_error'), 400);
                    }

                    // 获取文件名
                    $fileName = sprintf('%s_%s', date('YmdHis'), $file->getClientOriginalName());

                    $browsePath = sprintf('%s%s', env('UPLOAD_SAVE_PATH'), $fileName);

                    // 将文件移动到指定目录
                    $file->storeAs(env('UPLOAD_SAVE_PATH'), $fileName);

                    $data[] = $browsePath;
                }

            } else {
                // 单文件上传

                // 验证上传的文件
                $extension = $files->getClientOriginalExtension();//文件扩展名
                if (!in_array($extension, ConstantArr::$uploadExtension)) {
                    throw new \ErrorException(__('lang.file_suffix_error'), 400);
                }

                // 获取文件名
                $fileName = sprintf('%s_%s', date('YmdHis'), $files->getClientOriginalName());

                $browsePath = sprintf('%s%s', env('UPLOAD_SAVE_PATH'), $fileName);

                // 将文件移动到指定目录
                $files->storeAs(env('UPLOAD_SAVE_PATH'), $fileName);

                $data = [ $browsePath ];
            }

            return returnData(200, __('lang.upload_success'), $data);

        } catch (Exception $exception) {
            return returnData($exception->getCode(), $exception->getMessage(), [], handleErrorData($exception), $exception);
        }


    }




}
