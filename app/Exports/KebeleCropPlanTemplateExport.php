<?php

namespace App\Exports;

use App\Models\Crop;
use App\Models\CropType;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;

class KebeleCropPlanTemplateExport implements WithHeadings, WithEvents, WithTitle
{
    use Exportable;

    protected $cropTypes;
    protected $cropsByType;

    public function __construct()
    {
        $user = Auth::user();

        // Crop types
        $this->cropTypes = CropType::pluck('name')->toArray();

        // Crops grouped by crop type
        $this->cropsByType = Crop::with('cropType')
            ->get()
            ->groupBy(fn($c) => $c->cropType->name)
            ->map(fn($group) => $group->pluck('name')->toArray())
            ->toArray();
    }

    public function headings(): array
    {
        return [
            'year',             // Integer
            'crop_type',        // Dropdown
            'crop',             // Dependent dropdown
            'season',           // Dropdown (Meher, Belg, Kiremt)
            'area_to_plant',    // Decimal
            'productivity',     // Optional
            'responsible_staff',// Optional
            'notes',            // Optional
        ];
    }

    public function title(): string
    {
        return 'Crop Plans';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = 1000; // Max rows for dropdowns

                // 1️⃣ Crop Type dropdown (B2:B1000)
                $sheet->getDataValidation('B2:B'.$highestRow)
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                    ->setAllowBlank(false)
                    ->setFormula1('"'.implode(',', $this->cropTypes).'"')
                    ->setShowInputMessage(true)
                    ->setPromptTitle('Select Crop Type')
                    ->setPrompt('Please select a crop type from the list.');

                // 2️⃣ Season dropdown (D2:D1000)
                $sheet->getDataValidation('D2:D'.$highestRow)
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                    ->setAllowBlank(false)
                    ->setFormula1('"Meher,Belg,Kiremt"')
                    ->setShowInputMessage(true)
                    ->setPromptTitle('Select Season')
                    ->setPrompt('Please select a season from the list.');

                // 3️⃣ Add named ranges for each crop type
                $col = 10; // starting column for hidden lists
                foreach ($this->cropsByType as $type => $crops) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->fromArray($crops, null, $colLetter.'1');
                    // Create named range (replace spaces with _)
                    $name = preg_replace('/\s+/', '_', $type);
                    $sheet->getParent()->addNamedRange(
                        new \PhpOffice\PhpSpreadsheet\NamedRange($name, $sheet, $colLetter.'1:'.$colLetter.count($crops))
                    );
                    $col++;
                }

                // 4️⃣ Crop dropdown using INDIRECT (C2:C1000)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->getCell('C'.$row)->getDataValidation()
                        ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                        ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                        ->setAllowBlank(false)
                        ->setFormula1('INDIRECT(SUBSTITUTE(B'.$row.'," ","_"))')
                        ->setShowInputMessage(true)
                        ->setPromptTitle('Select Crop')
                        ->setPrompt('Select a crop based on the selected crop type.');
                }

                // Hide helper columns (J onwards)
                $highestCol = $sheet->getHighestColumn();
                for ($c = 10; $c <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol); $c++) {
                    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c))->setVisible(false);
                }
            },
        ];
    }
}
