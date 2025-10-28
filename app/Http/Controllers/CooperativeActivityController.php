<?php

namespace App\Http\Controllers;

use App\Models\CooperativeActivity;
use App\Models\CropType;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class CooperativeActivityController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.cooprative_activity', compact('cropTypes', 'planYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'baseline_year' => 'required|integer|min:2000|max:2100',
            'plan_year' => 'required|integer|min:2000|max:2100|gt:baseline_year',
            'cooperatives_count_baseline'=> 'nullable|integer|min:0',
            'cooperatives_count_plan'=> 'nullable|integer|min:0',
            'members_baseline' => 'nullable|integer|min:0',
            'members_plan' => 'nullable|integer|min:0',
            'capitals_baseline' => 'nullable|numeric|min:0',
            'capitals_plan' => 'nullable|numeric|min:0',
            'credit_taken_baseline' => 'nullable|numeric|min:0',
            'credit_taken_plan' => 'nullable|numeric|min:0',
            'credit_repaid_baseline' => 'nullable|numeric|min:0',
            'credit_repaid_plan' => 'nullable|numeric|min:0',
            'extension_services_baseline' => 'nullable|integer|min:0',
            'extension_services_plan' => 'nullable|integer|min:0',
            'union_membership_baseline' => 'nullable|integer|min:0',
            'union_membership_plan' => 'nullable|integer|min:0',
            'export_market_baseline' => 'nullable|integer|min:0',
            'export_market_plan' => 'nullable|integer|min:0',
        ]);

        try {
            // Create new cooperative activity record
            CooperativeActivity::create($validated);
            
            return redirect()->route('cooperative-activities.create')
                ->with('success', 'Cooperative activity data saved successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error saving data: ' . $e->getMessage());
        }
    }
}
