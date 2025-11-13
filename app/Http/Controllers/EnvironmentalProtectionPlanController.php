<?php

namespace App\Http\Controllers;

use App\Models\EnvironmentalActivity;
use Illuminate\Http\Request;
use App\Models\EnvironmentalProtectionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnvironmentalProtectionPlanController extends Controller
{
   public function index()
{
    $plans = EnvironmentalProtectionPlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'createdBy'])
        ->where('sector_id', Auth::user()->sector_id)
        ->orderBy('year', 'desc')
        ->paginate(10) // Add pagination with 10 items per page
        ->through(function ($plan) {
            $plan->environmental_data = is_array($plan->environmental_data) ? $plan->environmental_data : json_decode($plan->environmental_data, true);
            return $plan;
        });

    return view('sector.environmental-protection.index', compact('plans'));
}

    public function create()
    {
        $activities = EnvironmentalActivity::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.environmental-protection.create', compact('activities', 'years'));
    }

   public function store(Request $request)
{
    // Validate basic fields and ensure environmental_data is a string (JSON)
    $validated = $request->validate([
        'year' => 'required|string',
        'environmental_data' => 'required|string',
    ]);

    // Decode JSON data
    $environmentalData = json_decode($request->environmental_data, true);

    if (!is_array($environmentalData)) {
        return back()->withErrors(['environmental_data' => 'Invalid environmental data format.'])->withInput();
    }

    // Custom validation for environmental data
    $errors = [];
    $validActivities = [];

    foreach ($environmentalData as $index => $activity) {
        // Check if it's a special activity (Mana Fincaanii ijaarame)
        if (isset($activity['has_special_fields']) && $activity['has_special_fields'] === true) {
            // Validate special fields
            if (empty($activity['private_target']) || !is_numeric($activity['private_target']) || $activity['private_target'] <= 0) {
                $errors["environmental_data.{$index}.private_target"] = "Valid private target is required for {$activity['name']}";
            }
            if (empty($activity['public_target']) || !is_numeric($activity['public_target']) || $activity['public_target'] <= 0) {
                $errors["environmental_data.{$index}.public_target"] = "Valid public target is required for {$activity['name']}";
            }
            if (empty($activity['unit'])) {
                $errors["environmental_data.{$index}.unit"] = "Unit is required for {$activity['name']}";
            }
            
            // Add to valid activities if no errors
            if (!isset($errors["environmental_data.{$index}.private_target"]) && 
                !isset($errors["environmental_data.{$index}.public_target"]) && 
                !isset($errors["environmental_data.{$index}.unit"])) {
                $validActivities[] = $activity;
            }
        } else {
            // Validate regular fields
            if (empty($activity['target']) || !is_numeric($activity['target']) || $activity['target'] <= 0) {
                $errors["environmental_data.{$index}.target"] = "Valid target is required for {$activity['name']}";
            }
            if (empty($activity['unit'])) {
                $errors["environmental_data.{$index}.unit"] = "Unit is required for {$activity['name']}";
            }
            
            // Add to valid activities if no errors
            if (!isset($errors["environmental_data.{$index}.target"]) && 
                !isset($errors["environmental_data.{$index}.unit"])) {
                $validActivities[] = $activity;
            }
        }
    }

    // Check if we have at least one valid activity
    if (empty($validActivities)) {
        $errors['environmental_data'] = 'Please select at least one environmental activity with valid data.';
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    $user = Auth::user();

    // Prevent duplicate year for same kebele and sector
    $yearExists = EnvironmentalProtectionPlan::where('year', $request->year)
        ->where('kebele_id', $user->kebele_id)
        ->where('sector_id', $user->sector_id)
        ->exists();

    if ($yearExists) {
        return back()->withErrors([
            'year' => "An environmental protection plan already exists for year {$request->year} in your kebele and sector. Please edit the existing plan instead of creating a new one."
        ])->withInput();
    }

    // Add activity IDs using the valid activities
    foreach ($validActivities as &$activity) {
        $model = EnvironmentalActivity::where('name', $activity['name'])->first();
        $activity['id'] = $model?->id;
    }

    // Create new plan with valid activities only
    EnvironmentalProtectionPlan::create([
        'year' => $request->year,
        'environmental_data' => $validActivities,
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
        'created_by' => $user->id,
    ]);

    return redirect()->route('sector.environmental-plans.index')
        ->with('success', 'Environmental protection plan created successfully!');
}

    public function edit($id)
    {
        $plan = EnvironmentalProtectionPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $activities = EnvironmentalActivity::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.environmental-protection.edit', compact('plan', 'activities', 'years'));
    }

 public function update(Request $request, $id)
{
    $environmentalPlan = EnvironmentalProtectionPlan::findOrFail($id);

    // Validate basic fields
    $validated = $request->validate([
        'year' => 'required|string',
        'environmental_data' => 'required|string',
    ]);

    // Decode JSON data
    $environmentalData = json_decode($request->environmental_data, true);

    if (!is_array($environmentalData)) {
        return back()->withErrors(['environmental_data' => 'Invalid environmental data format.'])->withInput();
    }

    // Custom validation for environmental data
    $errors = [];
    $validActivities = [];

    foreach ($environmentalData as $index => $activity) {
        // Check if it's a special activity (Mana Fincaanii ijaarame)
        if (isset($activity['has_special_fields']) && $activity['has_special_fields'] === true) {
            // Validate special fields
            if (empty($activity['private_target']) || !is_numeric($activity['private_target']) || $activity['private_target'] <= 0) {
                $errors["environmental_data.{$index}.private_target"] = "Valid private target is required for {$activity['name']}";
            }
            if (empty($activity['public_target']) || !is_numeric($activity['public_target']) || $activity['public_target'] <= 0) {
                $errors["environmental_data.{$index}.public_target"] = "Valid public target is required for {$activity['name']}";
            }
            if (empty($activity['unit'])) {
                $errors["environmental_data.{$index}.unit"] = "Unit is required for {$activity['name']}";
            }
            
            // Add to valid activities if no errors
            if (!isset($errors["environmental_data.{$index}.private_target"]) && 
                !isset($errors["environmental_data.{$index}.public_target"]) && 
                !isset($errors["environmental_data.{$index}.unit"])) {
                $validActivities[] = $activity;
            }
        } else {
            // Validate regular fields
            if (empty($activity['target']) || !is_numeric($activity['target']) || $activity['target'] <= 0) {
                $errors["environmental_data.{$index}.target"] = "Valid target is required for {$activity['name']}";
            }
            if (empty($activity['unit'])) {
                $errors["environmental_data.{$index}.unit"] = "Unit is required for {$activity['name']}";
            }
            
            // Add to valid activities if no errors
            if (!isset($errors["environmental_data.{$index}.target"]) && 
                !isset($errors["environmental_data.{$index}.unit"])) {
                $validActivities[] = $activity;
            }
        }
    }

    // Check if we have at least one valid activity
    if (empty($validActivities)) {
        $errors['environmental_data'] = 'Please select at least one environmental activity with valid data.';
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    // Add activity IDs using the valid activities
    foreach ($validActivities as &$activity) {
        $model = EnvironmentalActivity::where('name', $activity['name'])->first();
        $activity['id'] = $model?->id;
    }

    // Update plan with valid activities only
    $environmentalPlan->update([
        'year' => $request->year,
        'environmental_data' => $validActivities,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('sector.environmental-plans.index')
        ->with('success', 'Environmental protection plan updated successfully!');
}

    public function destroy($id)
    {
        $plan = EnvironmentalProtectionPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.environmental-plans.index')->with('success', 'Environmental protection plan deleted successfully!');
    }
}