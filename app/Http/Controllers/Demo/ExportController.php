<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Excel\MoreImgExport;
use App\Http\Excel\MoreSheetExport;
use App\Http\Excel\SampleExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController {

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

    /**
     * @Desc:多个单元格中导出图片
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author: wanf
     * @Time: 2023/11/17 8:48
     */
    public function moreImgExport() {
        $data = [
            [
                'username' => 'c5',
                'id' => '665',
                'status' => '已签到',
                'sign_time' => '2023-11-16 19:57:00',
                'sign_start_time' => '2023-11-16 19:56:44',
                'terminal_name' => 't5',
                'room_name' => 'xxp',
                'sign_file' => 'D:/phpstudy_pro/WWW/laravel9.52.15/public/imgs/1.jpeg',
            ],
            [
                'username' => 'admin05',
                'id' => '664',
                'status' => '未签到',
                'sign_time' => '',
                'sign_start_time' => '2023-11-16 19:56:44',
                'terminal_name' => 't5',
                'room_name' => 'xxp',
                'sign_file' => '',
            ],
            [
                'username' => 'c5',
                'id' => '665',
                'status' => '已签到',
                'sign_time' => '2023-11-16 19:57:00',
                'sign_start_time' => '2023-11-16 19:56:44',
                'terminal_name' => 't5',
                'room_name' => 'xxp',
                'sign_file' => 'D:\phpstudy_pro\WWW\laravel9.52.15\public\imgs\1.jpg',
            ],
        ];

        return Excel::download(new MoreImgExport($data), '多个单元格中导出图片.xls');
    }

    /**
     * @Desc:生成excel文件保存到指定目录，并将生成文件打包为zip，保存到指定文件
     * @return \Illuminate\Http\JsonResponse
     * @author: wanf
     * @Time: 2023/11/27 19:04
     */
    public function zipExport() {
        $data1 = [
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
        $data2 = [
            [
                'nickname' => '昵称1',
                'username' => '用户名1',
                'account' => '账号1',
                'phone' => '13133333333',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nickname' => '昵称2',
                'username' => '用户名2',
                'account' => '账号2',
                'phone' => '13144444444',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $excel1 = 'excel1.xlsx';
        $excel2 = 'excel2.xlsx';

        Excel::store(new SampleExport($data1, '用户列表'), $excel1, 'download');
        Excel::store(new SampleExport($data2, '用户列表'), $excel2, 'download');

        $zipName = sprintf('%s%s', env('download_file_path'), 'test.zip');

        // zip创建文件
        $zip = new \ZipArchive();

        if ($zip->open($zipName, \ZipArchive::CREATE) !== true) {
            return returnData(400, '压缩包创建失败');
        }

        $excelFile1 = sprintf('%s%s', env('download_file_path'), $excel1);
        $excelFile2 = sprintf('%s%s', env('download_file_path'), $excel2);

        $zip->addFile($excelFile1, $excel1);
        $zip->addFile($excelFile2, $excel2);

        $zip->close();

        return returnData(200, sprintf('压缩包创建成功，请查看：%s', $zipName), $zipName);
    }

}
