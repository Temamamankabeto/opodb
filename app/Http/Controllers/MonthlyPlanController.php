<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropMonthlyPlan;
use App\Models\KarorQonnaWoggaa;
use App\Models\TotalCropYearlyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyPlanController extends Controller
{
    public function create()
    {
        $crops = Crop::all();
        $yearlyPlans = TotalCropYearlyPlan::all();
        return view('karora.karoora-gandaa.karora-jiaa.create', compact('yearlyPlans','crops'));
    }

    public function store(Request $request)
{
    $request->validate([
        'yearly_plan_id' => 'required|exists:yearly_plans,id',
        'weekly_land' => 'required|array',
    ]);

    $yearlyPlan = TotalCropYearlyPlan::findOrFail($request->yearly_plan_id);
    $weeklyData = $request->input('weekly_land');

    foreach ($weeklyData as $month => $weeks) {
        $monthlyTotal = 0;

        foreach ($weeks as $week => $land) {
            if ($land !== null && $land !== '') {
                $landFloat = floatval($land);

                // Save individual week
                CropMonthlyPlan::create([
                    'yearly_plan_id' => $yearlyPlan->id,
                    'month' => $month,
                    'week' => $week,
                    'land_hectares' => $landFloat,
                    'is_consolidated' => false,
                ]);

                $monthlyTotal += $landFloat;
            }
        }

        // Save monthly total as a consolidated row (week = 'Total')
        CropMonthlyPlan::create([
            'yearly_plan_id' => $yearlyPlan->id,
            'month' => $month,
            'week' => 'Total',
            'land_hectares' => $monthlyTotal,
            'is_consolidated' => true,
        ]);
    }

    return redirect()->route('monthly-plans.create')->with('success', 'Weekly data and monthly consolidated totals saved.');
}


public function getFilteredPlans(Request $request)
{
    $request->validate([
        'crop_id' => 'required|integer',
        'season' => 'required|string',
    ]);

    $userKebele = Auth::user()->kebele_id;

    $plans = TotalCropYearlyPlan::where('crop_id', $request->crop_id)
        ->where('season', $request->season)
        ->where('kebele_id', $userKebele)
        ->get(['id', 'year', 'total_land_hectares', 'name']);

    return response()->json($plans);
}
}
