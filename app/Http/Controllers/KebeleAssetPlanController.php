<?php

namespace App\Http\Controllers;

use App\Models\KebeleAssetPlan;
use App\Models\KebeleGovernmentProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebeleAssetPlanController extends Controller
{
    public function index()
    {
        // Show only plans for the logged-in user's kebele
        $plans = KebeleAssetPlan::where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return view('kebele_asset_plans.index', compact('plans'));
    }

    public function create()
    {
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);
        $activities = KebeleGovernmentProperty::all();

        return view('kebele_asset_plans.create', compact('activities', 'years'));
    }

   public function store(Request $request)
{
    $request->validate([
        'year' => 'required',
        'kebele_asset_plan' => 'required|array',
    ]);

    // Add dynamic validation for each activity type
    foreach ($request->kebele_asset_plan as $activityId => $data) {
        if (isset($data['selected']) && $data['selected'] == 1) {
            $activity = KebeleGovernmentProperty::find($activityId);
            
            if ($activity) {
                switch ($activity->name) {
                    case 'Galii Mootummaa':
                    case 'Hojiiwwan Tajaajila Lammummaa':
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.amount" => 'nullable|numeric|min:0',
                        ]);
                        break;
                        
                    case 'Buusaa Gonofaa':
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.members" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.membership_fee" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.contribution_kind" => 'nullable|string',
                            "kebele_asset_plan.{$activityId}.monetary_contribution" => 'nullable|numeric|min:0',
                        ]);
                        break;
                        
                    case 'Gaachena Sirnaa':
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.transferred_persons" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.daily_served_persons" => 'nullable|numeric|min:0',
                        ]);
                        break;
                        
                    case 'Fudhannaa Liqi':
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.received_this_year" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.returned_this_year" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.not_returned" => 'nullable|numeric|min:0',
                        ]);
                        break;
                        
                    case 'Qusannaa Baankii Siinqee':
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.family_amount" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.association_amount" => 'nullable|numeric|min:0',
                        ]);
                        break;
                        
                    default:
                        $request->validate([
                            "kebele_asset_plan.{$activityId}.quantity" => 'nullable|numeric|min:0',
                            "kebele_asset_plan.{$activityId}.cost" => 'nullable|numeric|min:0',
                        ]);
                }
            }
        }
    }

    // Check for existing plan
    $existingPlan = KebeleAssetPlan::where('year', $request->year)
        ->where('kebele_id', Auth::user()->kebele_id)
        ->first();

    if ($existingPlan) {
        return back()->with('error', 'A kebele asset plan already exists for ' . $request->year . ' in your kebele. Please edit the existing plan instead.')
            ->withInput();
    }

    $selectedActivities = [];
    foreach ($request->kebele_asset_plan as $activityId => $data) {
        if (isset($data['selected']) && $data['selected'] == 1) {
            $activity = KebeleGovernmentProperty::find($activityId);
            if ($activity) {
                $activityData = [
                    'name' => $activity->name ?? 'Unknown',
                    'unit' => $activity->unit ?? '',
                ];

                // Add all submitted fields based on activity type
                switch ($activity->name) {
                    case 'Galii Mootummaa':
                    case 'Hojiiwwan Tajaajila Lammummaa':
                        $activityData['amount'] = $data['amount'] ?? 0;
                        break;
                        
                    case 'Buusaa Gonofaa':
                        $activityData['members'] = $data['members'] ?? 0;
                        $activityData['membership_fee'] = $data['membership_fee'] ?? 0;
                        $activityData['contribution_kind'] = $data['contribution_kind'] ?? '';
                        $activityData['monetary_contribution'] = $data['monetary_contribution'] ?? 0;
                        break;
                        
                    case 'Gaachena Sirnaa':
                        $activityData['transferred_persons'] = $data['transferred_persons'] ?? 0;
                        $activityData['daily_served_persons'] = $data['daily_served_persons'] ?? 0;
                        break;
                        
                    case 'Fudhannaa Liqi':
                        $activityData['received_this_year'] = $data['received_this_year'] ?? 0;
                        $activityData['returned_this_year'] = $data['returned_this_year'] ?? 0;
                        $activityData['not_returned'] = $data['not_returned'] ?? 0;
                        break;
                        
                    case 'Qusannaa Baankii Siinqee':
                        $activityData['family_amount'] = $data['family_amount'] ?? 0;
                        $activityData['association_amount'] = $data['association_amount'] ?? 0;
                        break;
                        
                    default:
                        $activityData['quantity'] = $data['quantity'] ?? 0;
                        $activityData['cost'] = $data['cost'] ?? 0;
                }

                $selectedActivities[$activityId] = $activityData;
            }
        }
    }

    KebeleAssetPlan::create([
        'year' => $request->year,
        'kebele_asset_plan' => $selectedActivities,
        'kebele_id' => Auth::user()->kebele_id,
        'woreda_id' => Auth::user()->woreda_id,
        'zone_id' => Auth::user()->zone_id,
        'region_id' => Auth::user()->region_id,
        'created_by' => Auth::id(),
    ]);

    return redirect()->route('sector.kebele-asset-plans.index')
        ->with('success', 'Kebele Asset Plan Created Successfully');
}
 public function edit($id)
    {
        // Only allow editing of plans from the user's kebele
        $plan = KebeleAssetPlan::where('id', $id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();
        
        // Ensure kebele_asset_plan is an array
        if (is_string($plan->kebele_asset_plan)) {
            $plan->kebele_asset_plan = json_decode($plan->kebele_asset_plan, true);
        }
        
        $activities = KebeleGovernmentProperty::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('kebele_asset_plans.edit', compact('plan', 'activities', 'years'));
    }

    public function update(Request $request, $planId)
    {
        // Only allow updating of plans from the user's kebele
        $plan = KebeleAssetPlan::where('id', $planId)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();

        $request->validate([
            'year' => 'required',
            'kebele_asset_plan' => 'required|array',
        ]);

        // Add dynamic validation based on activity type
        foreach ($request->kebele_asset_plan as $activityId => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = KebeleGovernmentProperty::find($activityId);
                
                if ($activity) {
                    switch ($activity->name) {
                        case 'Galii Mootummaa':
                        case 'Hojiiwwan Tajaajila Lammummaa':
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.amount" => 'nullable|numeric|min:0',
                            ]);
                            break;
                            
                        case 'Buusaa Gonofaa':
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.members" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.membership_fee" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.contribution_kind" => 'nullable|string',
                                "kebele_asset_plan.{$activityId}.monetary_contribution" => 'nullable|numeric|min:0',
                            ]);
                            break;
                            
                        case 'Gaachena Sirnaa':
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.transferred_persons" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.daily_served_persons" => 'nullable|numeric|min:0',
                            ]);
                            break;
                            
                        case 'Fudhannaa Liqi':
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.received_this_year" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.returned_this_year" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.not_returned" => 'nullable|numeric|min:0',
                            ]);
                            break;
                            
                        case 'Qusannaa Baankii Siinqee':
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.family_amount" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.association_amount" => 'nullable|numeric|min:0',
                            ]);
                            break;
                            
                        default:
                            $request->validate([
                                "kebele_asset_plan.{$activityId}.quantity" => 'nullable|numeric|min:0',
                                "kebele_asset_plan.{$activityId}.cost" => 'nullable|numeric|min:0',
                            ]);
                    }
                }
            }
        }

        // Check for duplicate year (excluding current plan)
        $duplicatePlan = KebeleAssetPlan::where('year', $request->year)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->where('id', '!=', $planId)
            ->first();

        if ($duplicatePlan) {
            return back()->with('error', 'Another kebele asset plan already exists for ' . $request->year . ' in your kebele. Please use a different year.')
                ->withInput();
        }

        $selectedActivities = [];
        foreach ($request->kebele_asset_plan as $activityId => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $activity = KebeleGovernmentProperty::find($activityId);
                if ($activity) {
                    $activityData = [
                        'name' => $activity->name ?? 'Unknown',
                        'unit' => $activity->unit ?? '',
                    ];

                    // Add all submitted fields based on activity type
                    switch ($activity->name) {
                        case 'Galii Mootummaa':
                        case 'Hojiiwwan Tajaajila Lammummaa':
                            $activityData['amount'] = $data['amount'] ?? 0;
                            break;
                            
                        case 'Buusaa Gonofaa':
                            $activityData['members'] = $data['members'] ?? 0;
                            $activityData['membership_fee'] = $data['membership_fee'] ?? 0;
                            $activityData['contribution_kind'] = $data['contribution_kind'] ?? '';
                            $activityData['monetary_contribution'] = $data['monetary_contribution'] ?? 0;
                            break;
                            
                        case 'Gaachena Sirnaa':
                            $activityData['transferred_persons'] = $data['transferred_persons'] ?? 0;
                            $activityData['daily_served_persons'] = $data['daily_served_persons'] ?? 0;
                            break;
                            
                        case 'Fudhannaa Liqi':
                            $activityData['received_this_year'] = $data['received_this_year'] ?? 0;
                            $activityData['returned_this_year'] = $data['returned_this_year'] ?? 0;
                            $activityData['not_returned'] = $data['not_returned'] ?? 0;
                            break;
                            
                        case 'Qusannaa Baankii Siinqee':
                            $activityData['family_amount'] = $data['family_amount'] ?? 0;
                            $activityData['association_amount'] = $data['association_amount'] ?? 0;
                            break;
                            
                        default:
                            $activityData['quantity'] = $data['quantity'] ?? 0;
                            $activityData['cost'] = $data['cost'] ?? 0;
                    }

                    $selectedActivities[$activityId] = $activityData;
                }
            }
        }

        $plan->update([
            'year' => $request->year,
            'kebele_asset_plan' => $selectedActivities,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.kebele-asset-plans.index')
            ->with('success', 'Kebele Asset Plan Updated Successfully');
    }

    public function destroy($id)
    {
        // Only allow deletion of plans from the user's kebele
        $plan = KebeleAssetPlan::where('id', $id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->firstOrFail();
        
        $plan->delete();

        return redirect()->route('sector.kebele-asset-plans.index')
            ->with('success', 'Deleted Successfully');
    }
}