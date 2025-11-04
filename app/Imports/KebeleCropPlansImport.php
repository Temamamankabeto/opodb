<?php

namespace App\Imports;

use App\Models\Crop;
use App\Models\CropType;
use App\Models\KebeleCropPlan;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Exports\KebeleCropPlanTemplateExport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Facades\Excel;

class KebeleCropPlansImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = Auth::user();

        // Find crop type and crop
        $cropType = CropType::where('name', $row['crop_type'])->first();
        $crop = Crop::where('name', $row['crop'])->first();

        if (!$cropType || !$crop) {
            return null; // skip if invalid crop type or crop
        }

        // Fetch productivity from CropConfiguration if exists
        $config = CropConfiguration::where('kebele_id', $user->kebele_id)
            ->where('year', $row['year'])
            ->where('croptype_id', $cropType->id)
            ->where('crop_id', $crop->id)
            ->first();

        $productivity = $row['productivity'] ?? $config?->productivity_per_ha ?? 0;
        $area = $row['area_to_plant'] ?? 0;
        $expectedYield = $area * $productivity;

        return new KebeleCropPlan([
            'kebele_id' => $user->kebele_id,
            'sector_id' => $user->sector_id,
            'croptype_id' => $cropType->id,
            'crop_id' => $crop->id,
            'year' => $row['year'],
            'season' => $row['season'],
            'area_to_plant' => $area,
            'productivity' => $productivity,
            'expected_yield' => $expectedYield,
            'responsible_staff' => $row['responsible_staff'] ?? null,
            'notes' => $row['notes'] ?? null,
            'created_by' => $user->id,
        ]);
    }


    

}
