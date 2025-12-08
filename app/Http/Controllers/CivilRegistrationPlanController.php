<?php

namespace App\Http\Controllers;

use App\Models\CivilRegistrationPlan;
use App\Models\RegistrationOfCivilCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CivilRegistrationPlanController extends Controller
{
    public function index()
    {
        // Show only plans for the logged-in user's kebele
        $plans = CivilRegistrationPlan::where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return view('civil_registration_plans.index', compact('plans'));
    }

    public function create()
    {
        $activities = RegistrationOfCivilCertificate::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        
        return view('civil_registration_plans.create', compact('activities', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'civil_registration_activities' => 'required|array',
            'civil_registration_activities.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Check for existing plan for this kebele and year
        $existingPlan = CivilRegistrationPlan::where('year', $request->year)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->first();

        if ($existingPlan) {
            return back()->with('error', 'A civil registration plan already exists for ' . $request->year . ' in your kebele. Please edit the existing plan instead.')
                ->withInput();
        }

        $selectedActivities = [];
        foreach ($request->civil_registration_activities as $id => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = RegistrationOfCivilCertificate::find($id);
                if ($activity) {
                    $selectedActivities[$id] = [
                        'name' => $activity->name ?? 'Unknown',
                        'unit' => $activity->unit ?? '',
                        'quantity' => $data['quantity'] ?? 0,
                    ];
                }
            }
        }

        CivilRegistrationPlan::create([
            'year' => $request->year,
            'civil_registration_activities' => $selectedActivities,
            'kebele_id' => Auth::user()->kebele_id,
            'woreda_id' => Auth::user()->woreda_id,
            'zone_id' => Auth::user()->zone_id,
            'region_id' => Auth::user()->region_id,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('sector.civil_registration_plans.index')
            ->with('success', 'Civil Registration Plan created successfully.');
    }

    public function edit($id)
    {
        // Only allow editing of plans from the user's kebele
        $plan = CivilRegistrationPlan::where('id', $id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();
        
        $activities = RegistrationOfCivilCertificate::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        
        return view('civil_registration_plans.edit', compact('plan', 'activities', 'years'));
    }

    public function update(Request $request, $planId)  // Changed parameter name to avoid conflict
    {
        // Only allow updating of plans from the user's kebele
        $plan = CivilRegistrationPlan::where('id', $planId)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();

        $request->validate([
            'year' => 'required',
            'civil_registration_activities' => 'required|array',
            'civil_registration_activities.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Check for duplicate year (excluding current plan)
        $duplicatePlan = CivilRegistrationPlan::where('year', $request->year)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->where('id', '!=', $planId)
            ->first();

        if ($duplicatePlan) {
            return back()->with('error', 'Another civil registration plan already exists for ' . $request->year . ' in your kebele. Please use a different year.')
                ->withInput();
        }

        $selectedActivities = [];
        foreach ($request->civil_registration_activities as $activityId => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = RegistrationOfCivilCertificate::find($activityId);
                if ($activity) {
                    $selectedActivities[$activityId] = [
                        'name' => $activity->name ?? 'Unknown',
                        'unit' => $activity->unit ?? '',
                        'quantity' => $data['quantity'] ?? 0,
                    ];
                }
            }
        }

        $plan->update([
            'year' => $request->year,
            'civil_registration_activities' => $selectedActivities,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.civil_registration_plans.index')
            ->with('success', 'Civil Registration Plan updated successfully.');
    }

    public function destroy($id)
    {
        // Only allow deletion of plans from the user's kebele
        $plan = CivilRegistrationPlan::where('id', $id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();
        
        $plan->delete();

        return redirect()->route('sector.civil_registration_plans.index')
            ->with('success', 'Civil Registration Plan deleted successfully.');
    }
}