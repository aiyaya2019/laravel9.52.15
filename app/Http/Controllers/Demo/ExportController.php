<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Excel\MoreSheetExport;
use App\Http\Excel\SampleExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller {

    /**
     * @Desc:简单导出
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author: wanf
     * @Time: 2023/11/16 19:24
     */
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

    /**
     * @Desc:多工作簿导出
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author: wanf
     * @Time: 2023/11/16 19:24
     */
    public function moreSheetExport() {
        // 数据格式
        $data5 = [
            [
                'row_num' => 2,
                'data' =>
                    [
                        [
                            'type' => '关注名单',
                            'name' => '人员1',
                            'total' => '10',
                            'days' => 5,
                            'num' => 8,
                        ],
                        [
                            'type' => '关注名单',
                            'name' => '人员2',
                            'total' => '20',
                            'days' => 5,
                            'num' => 8,
                        ],
                    ],
            ],
            [
                'row_num' => 1,
                'data' =>
                    [
                        [
                            'type' => '介入名单',
                            'name' => '人员3',
                            'total' => '10',
                            'days' => 5,
                            'num' => 8,
                        ]
                    ],
            ],
            [
                'row_num' => 2,
                'data' =>
                    [
                        [
                            'type' => '公关名单',
                            'name' => '人员4',
                            'total' => '10',
                            'days' => 5,
                            'num' => 8,
                        ],
                        [
                            'type' => '公关名单',
                            'name' => '人员5',
                            'total' => '20',
                            'days' => 5,
                            'num' => 8,
                        ],
                    ],
            ],
        ];

        $data7 = [

            [
                'row_num' => 2,
                'data' =>
                    [
                        [
                            'type' => '人员1',
                            'name' => '10',
                            'total' => '100',
                            'days' => '',
                            'num' => '',
                        ],
                        [
                            'type' => '人员2',
                            'name' => '20',
                            'total' => '200',
                            'days' => '',
                            'num' => '',
                        ],
                    ],
            ],
            [
                'row_num' => 1,
                'data' =>
                    [
                        [
                            'type' => '人员3',
                            'name' => '30',
                            'total' => '300',
                            'days' => '',
                            'num' => '',
                        ]
                    ],
            ],
        ];

        $data = [
            'cusromer_warning' => [
                'sheet_name' => '客户预警',
                'data' => $data5,
            ],
            'product_warning' => [
                'sheet_name' => '产品预警',
                'data' => $data5,
            ],
            'customer_ranking' => [
                'sheet_name' => '客户排行榜',
                'data' => $data7,
            ],
        ];

        return Excel::download(new MoreSheetExport($data, '', $data['cusromer_warning']['data']), '多工作簿导出.xls');

    }

}
