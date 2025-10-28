<?php

namespace App\Http\Controllers;

use App\Models\ConstructionMaintenance;
use App\Models\CropType;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class ConstructionMaintenanceController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.energy', compact('cropTypes', 'planYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100|different:baseline_year',
            
            // Solar Center
            'solar_center_baseline' => 'nullable|numeric',
            'solar_center_plan' => 'nullable|numeric',
            
            // Biogas Construction
            'biogas_family_baseline' => 'nullable|numeric',
            'biogas_family_plan' => 'nullable|numeric',
            'biogas_community_baseline' => 'nullable|numeric',
            'biogas_community_plan' => 'nullable|numeric',
            'biogas_cost_baseline' => 'nullable|numeric',
            'biogas_cost_plan' => 'nullable|numeric',
            'biogas_idaama_baseline' => 'nullable|numeric',
            'biogas_idaama_plan' => 'nullable|numeric',
            
            // Electric Lines
            'electric_lines_baseline' => 'nullable|numeric',
            'electric_lines_plan' => 'nullable|numeric',
            
            // Rainfall Water Harvesting
            'rainfall_family_baseline' => 'nullable|numeric',
            'rainfall_family_plan' => 'nullable|numeric',
            'rainfall_community_baseline' => 'nullable|numeric',
            'rainfall_community_plan' => 'nullable|numeric',
            'rainfall_cost_baseline' => 'nullable|numeric',
            'rainfall_cost_plan' => 'nullable|numeric',
            'rainfall_idaama_baseline' => 'nullable|numeric',
            'rainfall_idaama_plan' => 'nullable|numeric',
            
            // Water Spring Development
            'water_spring_baseline' => 'nullable|numeric',
            'water_spring_plan' => 'nullable|numeric',
            
            // Hand Developed Well
            'well_family_baseline' => 'nullable|numeric',
            'well_family_plan' => 'nullable|numeric',
            'well_community_baseline' => 'nullable|numeric',
            'well_community_plan' => 'nullable|numeric',
            'well_cost_baseline' => 'nullable|numeric',
            'well_cost_plan' => 'nullable|numeric',
            'well_idaama_baseline' => 'nullable|numeric',
            'well_idaama_plan' => 'nullable|numeric',
            
            // Water Supply Maintenance
            'water_supply_maintenance_baseline' => 'nullable|numeric',
            'water_supply_maintenance_plan' => 'nullable|numeric',
            
            // New Road Construction
            'new_road_baseline' => 'nullable|numeric',
            'new_road_plan' => 'nullable|numeric',
            
            // Road Maintenance
            'road_maintenance_baseline' => 'nullable|numeric',
            'road_maintenance_plan' => 'nullable|numeric',
            
            // Water Furrow Construction
            'water_furrow_baseline' => 'nullable|numeric',
            'water_furrow_plan' => 'nullable|numeric',
            
            // Small Bridges Construction
            'small_bridges_baseline' => 'nullable|numeric',
            'small_bridges_plan' => 'nullable|numeric',
        ]);

        // Create new record
        ConstructionMaintenance::create($validated);

        return redirect()->route('construction_maintenance.create')
            ->with('success', 'Construction and maintenance data saved successfully!');
    }
}
