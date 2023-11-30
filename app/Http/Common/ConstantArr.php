<?php

namespace App\Http\Common;

/**
 * 全局常量数组类
 */
class ConstantArr {
    public static array $bannerPos = [
        Constant::HOME_BANNER,
        Constant::LIST_BANNER,
    ];

    // 导入excel文件格式，统一小写，自主转小写判断
    public static array $excelExtension = ['xls', 'xlsx'];

    // 允许上传的文件
    public static array $uploadExtension = ['xls', 'xlsx', 'jpg', 'jpeg', 'png', 'doc', 'txt', 'pdf'];

}
