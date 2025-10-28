<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\VillageResource;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class VillageResourceController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.mechinization', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100|gt:baseline_year',
            
            // Tractors in the village
            'tractors_private_baseline' => 'nullable|integer',
            'tractors_private_plan' => 'nullable|integer',
            'tractors_association_baseline' => 'nullable|integer',
            'tractors_association_plan' => 'nullable|integer',
            'tractors_idaama_baseline' => 'nullable|integer',
            'tractors_idaama_plan' => 'nullable|integer',
            
            // A combiner in the village
            'combiner_private_baseline' => 'nullable|integer',
            'combiner_private_plan' => 'nullable|integer',
            'combiner_association_baseline' => 'nullable|integer',
            'combiner_association_plan' => 'nullable|integer',
            'combiner_idaama_baseline' => 'nullable|integer',
            'combiner_idaama_plan' => 'nullable|integer',
            
            // Water pump/Motor in the village
            'water_pump_private_baseline' => 'nullable|integer',
            'water_pump_private_plan' => 'nullable|integer',
            'water_pump_association_baseline' => 'nullable|integer',
            'water_pump_association_plan' => 'nullable|integer',
            'water_pump_idaama_baseline' => 'nullable|integer',
            'water_pump_idaama_plan' => 'nullable|integer',
        ]);

        // Create or update the record
        VillageResource::updateOrCreate(
            [
                'baseline_year' => $validated['baseline_year'],
                'plan_year' => $validated['plan_year']
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
