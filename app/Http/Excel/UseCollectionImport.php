<?php

namespace App\Http\Excel;

use App\Models\UserModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class UseCollectionImport implements ToCollection {
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection) {

        // 第一个sheet的数据
        $collection = $collection->toArray();
        unset($collection[0]);//去掉说明
        unset($collection[1]);//去掉标题

        return $this->createData($collection);
    }

    public function createData($collection) {
        $data = [];
        foreach ($collection as $row) {
            $data[] = [
                'account' => $row[0],
                'username' => $row[1],
            ];
        }
        // (new UserModel())->insert($data);

        return true;
    }
}
