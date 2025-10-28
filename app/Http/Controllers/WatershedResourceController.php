<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\WatershedResource;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class WatershedResourceController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.qabenya_umama', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100',
            'watershed_length_baseline' => 'nullable|numeric',
            'watershed_length_plan' => 'nullable|numeric',
            'watershed_area_baseline' => 'nullable|numeric',
            'watershed_area_plan' => 'nullable|numeric',
            'hr_male_baseline' => 'nullable|integer',
            'hr_male_plan' => 'nullable|integer',
            'hr_female_baseline' => 'nullable|integer',
            'hr_female_plan' => 'nullable|integer',
            'hr_days_baseline' => 'nullable|integer',
            'hr_days_plan' => 'nullable|integer',
        ]);

        // The totals will be calculated automatically by the model
        WatershedResource::create($validated);

        return redirect()->back()->with('success', 'Data saved successfully!');
    }

    public function edit($id)
    {
        $data = WatershedResource::findOrFail($id);
        $planYears = range(2010, (int)date('Y') + 5);
        return view('your-edit-view', compact('data', 'planYears'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Same validation rules as store
        ]);

        $data = WatershedResource::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Data updated successfully!');
    }
}
