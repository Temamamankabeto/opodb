<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\ForestManagement;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class ForestManagementController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.eegumsa_nano', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100',
            'forest_area_baseline' => 'nullable|numeric',
            'forest_area_plan' => 'nullable|numeric',
            'grazing_free_area_baseline' => 'nullable|numeric',
            'grazing_free_area_plan' => 'nullable|numeric',
            'cooperatives_baseline' => 'nullable|integer',
            'cooperatives_plan' => 'nullable|integer',
            'cooperative_members_baseline' => 'nullable|integer',
            'cooperative_members_plan' => 'nullable|integer',
            'dry_waste_areas_baseline' => 'nullable|integer',
            'dry_waste_areas_plan' => 'nullable|integer',
            'latrine_private_baseline' => 'nullable|integer',
            'latrine_private_plan' => 'nullable|integer',
            'latrine_community_baseline' => 'nullable|integer',
            'latrine_community_plan' => 'nullable|integer',
        ]);

        // The totals will be calculated automatically by the model
        ForestManagement::create($validated);

        return redirect()->back()->with('success', 'Forest management data saved successfully!');
    }

    public function edit($id)
    {
        $data = ForestManagement::findOrFail($id);
        $planYears = range(2010, (int)date('Y') + 5);
        return view('forest-management.edit', compact('data', 'planYears'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Same validation rules as store
        ]);

        $data = ForestManagement::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Forest management data updated successfully!');
    }
}
