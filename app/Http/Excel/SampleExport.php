<?php

// namespace App\Exports\..\Http\Excel;
namespace App\Http\Excel;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle; //sheet名称
use Maatwebsite\Excel\Concerns\WithMultipleSheets;//sheet
use Maatwebsite\Excel\Concerns\WithStyles;//造型
use Maatwebsite\Excel\Concerns\WithColumnWidths; //列宽
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

/**
 * 简单导出类
 */
class SampleExport implements WithHeadings, FromCollection, WithTitle, WithColumnWidths, WithStyles, WithColumnFormatting{
    use Exportable;

    private $data;//数据
    private $status; //sheet名称
    private $column; //总行数

    public function __construct($data, $status = '') {
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * 表头
     * @return string[]
     */
    public function headings(): array {
        if (env('DEFAULT_LANGUAGE') == 'cn') {
            $header = ['昵称）', '用户名', '账号', '手机号', '时间'];
        } else {
            $header = ['Nickname', 'username', 'Account', 'Phone', 'Time'];
        }
        return $header;
    }

    /**
     * 导出
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    public function collection() {
        $data = $this->data;

        $list = [];
        foreach ($data as $key => $value) {
            $list[] = [
                'nickname' => $value['nickname'],
                'username' => $value['username'],
                'account' => $value['account'],
                'created_at' => $value['created_at'],
            ];
        }

        $this->column = count($list);

        return collect($list);
    }

    /**
     * sheet名称
     * @return string
     */
    public function title(): string {
        $language = config('app.locale');
        if ($language == 'cn') {
            $sheetName = '用户';
        } else {
            $sheetName = 'User';
        }
        return $sheetName;
    }

    /**
     * 设置列宽
     * @return array
     */
    public function columnWidths(): array {
        return [
            'A' => 30, 'B' => 30, 'C' => 30, 'D' => 30, 'F' => 30,
        ];
    }

    /**
     * 格式化列
     * @return array
     */
    public function columnFormats(): array {
        return [];
    }

    /**
     * 样式设置
     * @param Worksheet $sheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function styles(Worksheet $sheet) {

        $sheet->getRowDimension('1')->setRowHeight(36);

        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(18)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->getStyle('A1:F1')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '050505'],
        ]);

        $sheet->getStyle('A1:F1'.$this->column)->getAlignment()->setVertical('center');//垂直居中
        $sheet->getStyle('A1:F1'.$this->column)->applyFromArray(['alignment' => ['horizontal' => 'center']]);//设置水平居中
    }



}
