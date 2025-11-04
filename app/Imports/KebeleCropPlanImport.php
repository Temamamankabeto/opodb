<?php

namespace App\Imports;

use App\Models\KebeleCropPlan;
use App\Models\Crop;
use App\Models\CropType;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class KebeleCropPlanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $user = Auth::user();

        // Find crop type and crop
        $cropType = CropType::where('name', $row['crop_type'])->first();
        $crop = Crop::where('name', $row['crop'])->first();

        if (!$cropType || !$crop) return null;

        // Fetch configuration for productivity & fertilizer
        $config = CropConfiguration::where('kebele_id', $user->kebele_id)
            ->where('year', $row['year'])
            ->where('croptype_id', $cropType->id)
            ->where('crop_id', $crop->id)
            ->first();

        $productivity = $row['productivity'] ?? $config?->productivity_per_ha ?? 0;
        $expectedYield = $row['area_to_plant'] * $productivity;

        // Prevent duplicate
        $existing = KebeleCropPlan::where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
            ->where('year', $row['year'])
            ->where('season', $row['season'])
            ->where('croptype_id', $cropType->id)
            ->where('crop_id', $crop->id)
            ->first();

        if ($existing) return null;

        return new KebeleCropPlan([
            'kebele_id' => $user->kebele_id,
            'sector_id' => $user->sector_id,
            'croptype_id' => $cropType->id,
            'crop_id' => $crop->id,
            'year' => $row['year'],
            'season' => $row['season'],
            'area_to_plant' => $row['area_to_plant'],
            'productivity' => $productivity,
            'expected_yield' => $expectedYield,
            'responsible_staff' => $row['responsible_staff'] ?? null,
            'notes' => $row['notes'] ?? null,
            'created_by' => $user->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'year' => 'required|integer|min:2000|max:2100',
            'crop_type' => 'required|string|exists:crop_types,name',
            'crop' => 'required|string|exists:crops,name',
            'season' => 'required|string|in:Meher,Belg,Kiremt',
            'area_to_plant' => 'required|numeric|min:0',
            'productivity' => 'nullable|numeric|min:0',
        ];
    }
}
