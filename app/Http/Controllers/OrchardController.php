<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\OrchardData;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrchardController extends Controller
{
    public function create()
    { $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.biqiltu_orchard', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
            
            // Orchard fields
            'orchard_area_baseline' => 'nullable|numeric',
            'orchard_area_plan' => 'nullable|numeric',
            'orchard_participant_baseline' => 'nullable|integer',
            'orchard_participant_plan' => 'nullable|integer',
            'orchard_days_baseline' => 'nullable|integer',
            'orchard_days_plan' => 'nullable|integer',
            
            // Seedling fields
            'signal_baseline' => 'nullable|numeric',
            'signal_plan' => 'nullable|numeric',
            'sweetener_baseline' => 'nullable|numeric',
            'sweetener_plan' => 'nullable|numeric',
            'coffee_baseline' => 'nullable|numeric',
            'coffee_plan' => 'nullable|numeric',
            'tea_baseline' => 'nullable|numeric',
            'tea_plan' => 'nullable|numeric',
            'seedling_participant_baseline' => 'nullable|integer',
            'seedling_participant_plan' => 'nullable|integer',
            'seedling_days_baseline' => 'nullable|integer',
            'seedling_days_plan' => 'nullable|integer',
            
            // Fruits fields
            'avokaadoo_baseline' => 'nullable|numeric',
            'avokaadoo_plan' => 'nullable|numeric',
            'paappaayaa_baseline' => 'nullable|numeric',
            'paappaayaa_plan' => 'nullable|numeric',
            'appilli_baseline' => 'nullable|numeric',
            'appilli_plan' => 'nullable|numeric',
            'fruits_others_baseline' => 'nullable|numeric',
            'fruits_others_plan' => 'nullable|numeric',
            'fruits_participant_baseline' => 'nullable|integer',
            'fruits_participant_plan' => 'nullable|integer',
            'fruits_days_baseline' => 'nullable|integer',
            'fruits_days_plan' => 'nullable|integer',
            
            // Calculated fields
            'fruits_idaama_baseline' => 'nullable|numeric',
            'fruits_idaama_plan' => 'nullable|numeric',
            'total_number_of_participant_baseline' => 'nullable|integer',
            'total_number_of_participant_plan' => 'nullable|integer',
            'total_number_of_days_baseline' => 'nullable|integer',
            'total_number_of_days_plan' => 'nullable|integer',
        ]);

        try {
            // Create new orchard data record
            OrchardData::create($validated);
            
            return redirect()->route('orchard.create')
                ->with('success', 'Orchard data saved successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error saving data: ' . $e->getMessage());
        }
    }

    public function getPlanYears()
    {
        return OrchardData::select(DB::raw('DISTINCT plan_year as year'))
            ->orderBy('year', 'desc')
            ->pluck('year');
    }
}
