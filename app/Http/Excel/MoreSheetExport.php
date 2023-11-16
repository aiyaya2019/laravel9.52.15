<?php

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
 * 多工作簿导出类
 */
class MoreSheetExport implements WithHeadings, FromCollection, WithTitle, WithColumnWidths, WithStyles, WithColumnFormatting, WithMultipleSheets{
    use Exportable;

    private $data;//数据
    private $sheetName = ''; //sheet名称
    private $column; //总行数
    private $sheetData = [];
    private $header;

    public function __construct($data, $sheetName = '', $sheetData = [], $header = 1) {
        $this->data = $data;
        $this->sheetName = $sheetName;
        $this->sheetData = $sheetData;
        $this->header = $header;
    }

    /**
     * 表头
     * @return string[]
     */
    public function headings(): array {
        if ($this->header == 1) {
            $header = ['预警类别', '客户名称', '售后总数', '售后涉及天数', '售后涉及产品数'];
        } else {
            $header = ['客户名称', '客户问题售后数', '产生费用'];
        }
        return $header;
    }

    /**
     * 导出
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    public function collection() {
        $sheetData = $this->sheetData;
        $list = [];
        foreach ($sheetData as $key => $value) {
            foreach ($value['data'] as $val) {
                $list[] = [
                    'type' => $val['type'],
                    'name' => $val['name'],
                    'total' => $val['total'],
                    'days' => $val['days'],
                    'num' => $val['num'],
                ];
            }
        }
        $this->column = count($list);

        return collect($list);
    }

    /**
     * 格式化列
     * @return array
     */
    public function columnFormats(): array {
        return [];
    }

    /**
     * 设置列宽
     * @return array
     */
    public function columnWidths(): array {
        return [
            'A' => 40, 'B' => 40, 'C' => 40, 'D' => 40, 'E' => 40
        ];
    }

    /**
     * 样式设置
     * @param Worksheet $sheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function styles(Worksheet $sheet) {
        $sheet->getRowDimension('1')->setRowHeight(24);

        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->getColor();
        $sheet->getStyle('A1:G1'.$this->column)->getAlignment()->setVertical('center');//垂直居中
        $sheet->getStyle('A1:G1'.$this->column)->applyFromArray(['alignment' => ['horizontal' => 'center']]);//设置水平居中

        $row = 2;

        foreach ($this->sheetData as $value) {
            $endRow = $row + $value['row_num'] - 1;
            $sheet->mergeCells("A$row:A$endRow");
            $row = $endRow + 1;
        }
    }

    /**
     * sheet名称
     * @return string
     */
    public function title(): string {
        return $this->sheetName;
    }

    /**
     * 多工作簿
     * @return string
     */
    public function sheets(): array {
        $sheets = [];

        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
            $header = $key == 'customer_ranking' ? 2 : 1;
            $sheets[] = new MoreSheetExport($this->data, $value['sheet_name'], $value['data'], $header);
        }

        return $sheets;
    }
}
