<?php

namespace App\Http\Excel;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

//sheet名称
//sheet
//造型
//列宽

/**
 * 多个单元格中导出图片
 */
class MoreImgExport implements WithHeadings, FromCollection, WithTitle, WithColumnWidths, WithStyles, WithColumnFormatting, WithEvents{
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
            $header = ['姓名', '会议室', '终端编号', '状态', '开启签到时间', '签到时间', '签到凭证'];
        } else {
            $header = ['Name', 'Meeting Room', 'Terminal ID', 'Status', 'Start time', 'Sign-in time', 'Sign-in evidence'];
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
                'username' => $value['username'],
                'room_name' => $value['room_name'],
                'terminal_name' => $value['terminal_name'],
                'status' => $value['status'],
                'sign_start_time' => $value['sign_start_time'],
                'sign_time' => $value['sign_time'],
                'sign_file' => '',
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
            $sheetName = '人员签到';
        } else {
            $sheetName = 'User Sign in';
        }
        return $sheetName;
    }

    /**
     * 设置列宽
     * @return array
     */
    public function columnWidths(): array {
        return [
            'A' => 40, 'B' => 40, 'C' => 40, 'D' => 40, 'E' => 40, 'F' => 40, 'G' => 40
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
        $sheet->getDefaultRowDimension()->setRowHeight(80);//设置默认行高
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(18)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->getStyle('A1:G1')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '050505'],
        ]);

        $sheet->getStyle('A1:G1'.$this->column)->getAlignment()->setVertical('center');//垂直居中
        $sheet->getStyle('A1:G1'.$this->column)->applyFromArray(['alignment' => ['horizontal' => 'center']]);//设置水平居中
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->data as $key => $value) {
                    // 如果图片不存在跳出
                    if (!file_exists($value['sign_file'])) {
                        continue;
                    }
                    $drawing= new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('signatuer');
                    $drawing->setDescription('This is my signatuer');
                    $drawing->setPath($value['sign_file']);
                    $drawing->setHeight(80);
                    $drawing->setOffsetX('60');//設定圖片距離cell邊界位移
                    $drawing->setOffsetY('15');//設定圖片距離cell邊界位移
                    $drawing->setCoordinates('G'. ($key + 2));
                    $drawing->setWorksheet($event->sheet->getDelegate());
                }
            },
        ];
    }

}
