<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements FromView, WithColumnWidths, WithStyles
{
    use Exportable;

    public function setParameters($fecha, $objOffice, $arrShift, $arrSpeciality, $arrUserOffices)
    {
        $this->fecha = $fecha;
        $this->objOffice = $objOffice;
        $this->arrShift = $arrShift;
        $this->arrSpeciality = $arrSpeciality;
        $this->arrUserOffices = $arrUserOffices;
    }

    public function view(): View
    {
        return view('dashboard.contents.reports.pdf.GeneralReport', [
            'fechaReporte'  => $this->fecha,
            'office'        => $this->objOffice,
            'shifts'        => $this->arrShift,
            'specialities'  => $this->arrSpeciality,
            'advisers'      => $this->arrUserOffices
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 55,
            'B' => 35,
            'C' => 35,
            'D' => 35,
            'E' => 20
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'B1' => [
                'font' => [
                    'size' => 16,
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }
}
