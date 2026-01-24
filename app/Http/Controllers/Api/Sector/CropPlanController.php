<?php

namespace App\Http\Controllers\Api\Sector;

use App\Http\Controllers\Controller;
use App\Models\KebeleCropPlan;
use App\Models\CropType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CropPlanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
            ->where('kebele_id', $user->kebele_id)
            ->orderBy('year')
            ->orderBy('season')
            ->orderBy('croptype_id')
            ->get();

        // Grand totals
        $grandTotals = [
            'area' => $cropPlans->sum('area_to_plant'),
            'yield' => $cropPlans->sum('expected_yield'),
        ];

        // Yearly totals
        $yearlyTotals = $cropPlans
            ->groupBy('year')
            ->map(function ($plans) {
                return [
                    'total_area' => $plans->sum('area_to_plant'),
                    'total_yield' => $plans->sum('expected_yield'),
                ];
            });

        // Productivity comparison
        $currentYear = $cropPlans->max('year');
        $previousYear = $currentYear - 1;

        $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
        $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');

        $productivity = [];

        foreach ($currentYearData as $cropId => $plans) {
            $productivity[$cropId] = [
                'current_avg' => round($plans->avg('productivity'), 2),
                'previous_avg' => 0,
            ];
        }

        foreach ($previousYearData as $cropId => $plans) {
            $productivity[$cropId]['previous_avg'] =
                round($plans->avg('productivity'), 2);
        }

        return response()->json([
            'data' => $cropPlans,
            'meta' => [
                'current_year' => $currentYear,
                'previous_year' => $previousYear,
                'grand_totals' => $grandTotals,
                'yearly_totals' => $yearlyTotals,
                'productivity' => $productivity,
            ],
        ]);
    }
}
