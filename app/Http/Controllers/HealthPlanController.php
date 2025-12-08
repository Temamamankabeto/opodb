<?php

namespace App\Http\Controllers;

use App\Models\HealthPlan;
use App\Models\Health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthPlanController extends Controller
{
    public function index()
    {
        $plans = HealthPlan::where('kebele_id', Auth::user()->kebele_id)->latest()->get();
        return view('health_plans.index', compact('plans'));
    }

    public function create()
    {
        $activities = Health::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        return view('health_plans.create', compact('activities', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'health_activity_name' => 'required|array',
            'health_activity_name.*.amount' => 'nullable|numeric|min:0',
            'health_activity_name.*.budget' => 'nullable|numeric|min:0',
            'health_activity_name.*.type' => 'nullable|string|max:100',
        ]);

        $selectedActivities = [];
        foreach ($request->health_activity_name as $id => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $selectedActivities[$id] = [
                    'name' => Health::find($id)->name ?? 'Unknown',
                    'unit' => Health::find($id)->unit ?? '',
                    'amount' => $data['amount'] ?? 0,
                    'budget' => $data['budget'] ?? 0,
                    'type' => Health::find($id)->type ?? '',
                ];
            }
        }

        HealthPlan::create([
            'year' => $request->year,
            'health_activity_name' => $selectedActivities,
            'kebele_id' => Auth::user()->kebele_id,
            'woreda_id' => Auth::user()->woreda_id,
            'zone_id' => Auth::user()->zone_id,
            'region_id' => Auth::user()->region_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('sector.health_plans.index')->with('success', 'Health Plan created successfully.');
    }

    public function edit($id)
    {
        $plan = HealthPlan::findOrFail($id);
        $activities = Health::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        
        return view('health_plans.edit', compact('plan', 'activities', 'years'));
    }

    public function update(Request $request, $planId)  // Changed parameter name to avoid conflict
    {
        $request->validate([
            'year' => 'required',
            'health_activity_name' => 'required|array',
            'health_activity_name.*.amount' => 'nullable|numeric|min:0',
            'health_activity_name.*.budget' => 'nullable|numeric|min:0',
            'health_activity_name.*.type' => 'nullable|string|max:100',
        ]);

        // Filter only selected activities
        $selectedActivities = [];
        foreach ($request->health_activity_name as $activityId => $data) {  // Changed variable name
            if (isset($data['selected']) && $data['selected'] == 1) {
                $selectedActivities[$activityId] = [  // Use activityId here
                    'name' => Health::find($activityId)->name ?? 'Unknown',  // Use activityId here
                    'unit' => Health::find($activityId)->unit ?? '',  // Use activityId here
                    'amount' => $data['amount'] ?? 0,
                    'budget' => $data['budget'] ?? 0,
                    'type' => Health::find($activityId)->type ?? '',  // Use activityId here
                ];
            }
        }

        $plan = HealthPlan::findOrFail($planId);  // Use $planId here

        $plan->update([
            'year' => $request->year,
            'health_activity_name' => $selectedActivities,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.health_plans.index')->with('success', 'Health Plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = HealthPlan::findOrFail($id);
        $plan->delete();
        return back()->with('success', 'Deleted successfully.');
    }
}