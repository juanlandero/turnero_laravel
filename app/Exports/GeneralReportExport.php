<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements WithStyles, WithHeadings, FromArray, ShouldAutoSize, WithDrawings
{
    use Exportable;

    public function setParameters($headings, $data)
    {
        $this->headings = $headings;
        $this->data = $data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'B1' => [
                'font' => [
                    'name' => 'Arial',
                    'size' => 16,
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            'A7:ZZ7' => [
                'font' => [
                    'name' => 'Arial',
                    'size' => 9.5,
                    'bold' => true,
                    'color' => array('rgb' => 'FFFFFF')
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '37464F'],
                ],
            ],
            'A8:ZZ100' => [
                'font' => [
                    'name' => 'Arial',
                    'size' => 9.5
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                for ($i = 7; $i < 50; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30, 'px');
                }
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/madero-logo.jpeg'));
        $drawing->setHeight(40);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
