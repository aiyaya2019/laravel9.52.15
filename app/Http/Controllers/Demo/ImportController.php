<?php

namespace App\Http\Controllers\Demo;

use App\Http\Common\ConstantArr;
use App\Http\Controllers\Controller;
use App\Http\Excel\Imports;
use App\Http\Excel\UseCollectionImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller {

    /**
     * @Desc:使用公用导入类进行导入，导入数据在控制器中处理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * @author: wanf
     * @Time: 2023/11/29 11:50
     */
    public function usePublicImport(Request $request) {
        $file = request('file');
        // $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();//文件扩展名
        if (!in_array($extension, ConstantArr::$excelExtension)) {
            return returnData(400, __('lang.file_suffix_error'));
        }

        if(str_starts_with('linux', 'win')) {
            //获取文件临时目录 windows环境
            $tempPath = $file->getRealPath();
        } else {
            //获取文件临时目录 linux环境
            $tempPath = storage_path('app') .'/' .$file->store('temp');
        }

        $array = Excel::toArray(new Imports, $tempPath);

        $array = $array[0];//第一个sheet
        unset($array[0]);//去掉说明
        unset($array[1]);//去掉标题

        echo 'excel的数据：';
        print_r($array);exit;
    }

    public function useCollectionImport(Request $request) {
        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();//文件扩展名
        if (!in_array($extension, ConstantArr::$excelExtension)) {
            return returnData(400, __('lang.file_suffix_error'));
        }

        if(str_starts_with('linux', 'win')) {
            //获取文件临时目录 windows环境
            $tempPath = $file->getRealPath();
        } else {
            //获取文件临时目录 linux环境
            $tempPath = storage_path('app') .'/' .$file->store('temp');
        }

        $res = Excel::import(new UseCollectionImport(), $tempPath);
        var_dump($res);

    }

}
