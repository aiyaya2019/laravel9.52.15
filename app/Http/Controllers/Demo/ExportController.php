<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Excel\SampleExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller {

    public function export() {
        $data = [
            [
                'nickname' => '昵称1',
                'username' => '用户名1',
                'account' => '账号1',
                'phone' => '13111111111',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nickname' => '昵称2',
                'username' => '用户名2',
                'account' => '账号2',
                'phone' => '13122222222',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        return Excel::download(new SampleExport($data, '用户列表'), 'sample_export.xlsx');
    }

}
