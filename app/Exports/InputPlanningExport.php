<?php

namespace App\Exports;

use App\Models\CallaGuddiftuu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InputPlanningExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $inputs;

    public function __construct($inputs)
    {
        $this->inputs = $inputs->load(['kebele', 'creator']); // Eager load relationships
    }

    public function collection()
    {
        return $this->inputs;
    }

    public function headings(): array
    {
        return [
            'Kebele',
            'Year',
            'Season',
            'Input Type',
            'Quantity',
            'Unit',
            'Planned By',
            'Phone'
        ];
    }

    public function map($input): array
    {
        return [
            $input->kebele->name ?? 'N/A',
            $input->year,
            $input->season,
            $input->type,
            $this->formatQuantity($input->quantity),
            strtoupper($input->unit),
            $input->creator ? $input->creator->name : ($input->user ? $input->user->name : 'Unknown'),
            $input->phone ?? 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '3490dc']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0.00'
                ]
            ],
            'A2:H' . ($this->inputs->count() + 1) => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'F8F9FA']
                ]
            ]
        ];
    }

    protected function formatQuantity($quantity)
    {
        return is_numeric($quantity) ? round($quantity, 2) : 0;
    }

}
