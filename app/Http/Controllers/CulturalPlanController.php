<?php

namespace App\Http\Controllers;

use App\Models\CulturalPlan;
use App\Models\CulturalRevival;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CulturalPlanController extends Controller
{
    public function index()
    {
        // Show only plans for the logged-in user's kebele
        $plans = CulturalPlan::where('kebele_id', Auth::user()->kebele_id)
                            ->orderBy('year', 'DESC')
                            ->orderBy('id', 'DESC')
                            ->get();
        return view('cultural_plans.index', compact('plans'));
    }

    public function create()
    {
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        $activities = CulturalRevival::all();
        
        return view('cultural_plans.create', compact('activities', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'cultural_activities' => 'required|array',
            'cultural_activities.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Check for existing plan for this kebele and year
        $existingPlan = CulturalPlan::where('year', $request->year)
                                   ->where('kebele_id', Auth::user()->kebele_id)
                                   ->first();

        if ($existingPlan) {
            return back()->with('error', 'A cultural plan already exists for ' . $request->year . ' in your kebele. Please edit the existing plan instead.')
                        ->withInput();
        }

        $selectedActivities = [];
        foreach ($request->cultural_activities as $id => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = CulturalRevival::find($id);
                if ($activity) {
                    $selectedActivities[$id] = [
                        'name' => $activity->name,
                        'quantity' => $data['quantity'] ?? 0,
                        'unit' => $activity->unit,
                    ];
                }
            }
        }

        CulturalPlan::create([
            'year' => $request->year,
            'cultural_activities' => $selectedActivities,
            // 'sector_id' => Auth::user()->sector_id,
            'kebele_id' => Auth::user()->kebele_id,
            'woreda_id' => Auth::user()->woreda_id,
            'zone_id' => Auth::user()->zone_id,
            'region_id' => Auth::user()->region_id,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('sector.cultural_plans.index')->with('success', 'Cultural Plan created successfully.');
    }

    public function edit($id)
    {
        // Only allow editing of plans from the user's kebele
        $plan = CulturalPlan::where('id', $id)
                           ->where('kebele_id', Auth::user()->kebele_id)
                           ->firstOrFail();
        
        $activities = CulturalRevival::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        
        return view('cultural_plans.edit', compact('plan', 'activities', 'years'));
    }

    public function update(Request $request, $id)
    {
        // Only allow updating of plans from the user's kebele
        $plan = CulturalPlan::where('id', $id)
                           ->where('kebele_id', Auth::user()->kebele_id)
                           ->firstOrFail();

        $request->validate([
            'year' => 'required|string|max:10',
            'cultural_activities' => 'required|array',
            'cultural_activities.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Check for duplicate year (excluding current plan)
        $duplicatePlan = CulturalPlan::where('year', $request->year)
                                    ->where('kebele_id', Auth::user()->kebele_id)
                                    ->where('id', '!=', $id)
                                    ->first();

        if ($duplicatePlan) {
            return back()->with('error', 'Another cultural plan already exists for ' . $request->year . ' in your kebele. Please use a different year.')
                        ->withInput();
        }

        $selectedActivities = [];
        foreach ($request->cultural_activities as $activityId => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = CulturalRevival::find($activityId);
                if ($activity) {
                    $selectedActivities[$activityId] = [
                        'name' => $activity->name,
                        'quantity' => $data['quantity'] ?? 0,
                        'unit' => $activity->unit,
                    ];
                }
            }
        }

        $plan->update([
            'year' => $request->year,
            'cultural_activities' => $selectedActivities,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.cultural_plans.index')->with('success', 'Cultural Plan updated successfully.');
    }

    public function destroy($id)
    {
        // Only allow deletion of plans from the user's kebele
        $plan = CulturalPlan::where('id', $id)
                           ->where('kebele_id', Auth::user()->kebele_id)
                           ->firstOrFail();
        
        $plan->delete();
        return redirect()->route('sector.cultural_plans.index')->with('success', 'Cultural Plan deleted successfully.');
    }
}