<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\NurseryDataEntry;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class NurserySeedlingController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.biqiltu_nursery', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100',
            'nursery_quantity_baseline' => 'nullable|numeric',
            'nursery_quantity_plan' => 'nullable|numeric',
            'nursery_area_baseline' => 'nullable|numeric',
            'nursery_area_plan' => 'nullable|numeric',
            'nursery_participant_baseline' => 'nullable|numeric',
            'nursery_participant_plan' => 'nullable|numeric',
            'nursery_days_baseline' => 'nullable|numeric',
            'nursery_days_plan' => 'nullable|numeric',
            'signal_baseline' => 'nullable|numeric',
            'signal_plan' => 'nullable|numeric',
            'sweetener_baseline' => 'nullable|numeric',
            'sweetener_plan' => 'nullable|numeric',
            'coffee_baseline' => 'nullable|numeric',
            'coffee_plan' => 'nullable|numeric',
            'tea_baseline' => 'nullable|numeric',
            'tea_plan' => 'nullable|numeric',
            'seedling_participant_baseline' => 'nullable|numeric',
            'seedling_participant_plan' => 'nullable|numeric',
            'seedling_days_baseline' => 'nullable|numeric',
            'seedling_days_plan' => 'nullable|numeric',
            'avokaadoo_baseline' => 'nullable|numeric',
            'avokaadoo_plan' => 'nullable|numeric',
            'paappaayaa_baseline' => 'nullable|numeric',
            'paappaayaa_plan' => 'nullable|numeric',
            'appilli_baseline' => 'nullable|numeric',
            'appilli_plan' => 'nullable|numeric',
            'fruits_others_baseline' => 'nullable|numeric',
            'fruits_others_plan' => 'nullable|numeric',
            'fruits_participant_baseline' => 'nullable|numeric',
            'fruits_participant_plan' => 'nullable|numeric',
            'fruits_days_baseline' => 'nullable|numeric',
            'fruits_days_plan' => 'nullable|numeric',
        ]);

        // Create a new crop plan
        $yearlyCropPlan = NurseryDataEntry::create([
            'baseline_year' => $request->input('baseline_year'),
            'plan_year' => $request->input('plan_year'),
            'nursery_quantity_baseline' => $request->input('nursery_quantity_baseline'),
            'nursery_quantity_plan' => $request->input('nursery_quantity_plan'),
            'nursery_area_baseline' => $request->input('nursery_area_baseline'),
            'nursery_area_plan' => $request->input('nursery_area_plan'),
            'nursery_participant_baseline' => $request->input('nursery_participant_baseline'),
            'nursery_participant_plan' => $request->input('nursery_participant_plan'),
            'nursery_days_baseline' => $request->input('nursery_days_baseline'),
            'nursery_days_plan' => $request->input('nursery_days_plan'),
            'signal_baseline' => $request->input('signal_baseline'),
            'signal_plan' => $request->input('signal_plan'),
            'sweetener_baseline' => $request->input('sweetener_baseline'),
            'sweetener_plan' => $request->input('sweetener_plan'),
            'coffee_baseline' => $request->input('coffee_baseline'),
            'coffee_plan' => $request->input('coffee_plan'),
            'tea_baseline' => $request->input('tea_baseline'),
            'tea_plan' => $request->input('tea_plan'),
            'seedling_participant_baseline' => $request->input('seedling_participant_baseline'),
            'seedling_participant_plan' => $request->input('seedling_participant_plan'),
            'seedling_days_baseline' => $request->input('seedling_days_baseline'),
            'seedling_days_plan' => $request->input('seedling_days_plan'),
            'avokaadoo_baseline' => $request->input('avokaadoo_baseline'),
            'avokaadoo_plan' => $request->input('avokaadoo_plan'),
            'paappaayaa_baseline' => $request->input('paappaayaa_baseline'),
            'paappaayaa_plan' => $request->input('paappaayaa_plan'),
            'appilli_baseline' => $request->input('appilli_baseline'),
            'appilli_plan' => $request->input('appilli_plan'),
            'fruits_others_baseline' => $request->input('fruits_others_baseline'),
            'fruits_others_plan' => $request->input('fruits_others_plan'),
            'fruits_participant_baseline' => $request->input('fruits_participant_baseline'),
            'fruits_participant_plan' => $request->input('fruits_participant_plan'),
            'fruits_days_baseline' => $request->input('fruits_days_baseline'),
            'fruits_days_plan' => $request->input('fruits_days_plan'),
        ]);

        // Redirect back with success message
        return redirect()->route('yearly_crop_plans.create')
            ->with('success', 'Nursery and Seedling Data Entry has been successfully saved!');
    }
}
