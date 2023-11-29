<?php

namespace App\Http\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

/**
 * 公用导入类。所有导入可用该类获取导入数据，但导入数据需要在controller中处理。
 * 如：Demo/ImportController中的sampleImport()
 *
 * 导入导出业务代码尽量不要和原来业务耦合，即表格数据在各自的导入/导出类中进行处理（这样会导致每一个导入导出都需要创建一个对应的导入导出类）
 */
class Imports implements ToCollection {
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection) {
        //
    }
}
