<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BuildingInstitutionPlan;
use Illuminate\Support\Facades\Validator;

class BuildingCommunityInstitutionPlanController extends Controller
{
    public function index()
    {
        $plans = BuildingInstitutionPlan::with(['institution', 'sector', 'kebele', 'woreda', 'zone', 'region', 'createdBy'])
            ->where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($plan) {
                $plan->new_building_data = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data, true);
                $plan->improvement_data = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data, true);
                return $plan;
            });

        return view('sector.building-institutions.index', compact('plans'));
    }

    public function create()
    {
        // Fetch institutions where institution_type_id = 1
        $institutions = Institution::where('institution_type_id', 1)->get();
            
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.building-institutions.create', compact('institutions', 'years'));
    }
public function store(Request $request)
{
    // Validate basic fields and ensure JSON data is strings
    $validated = $request->validate([
        'year' => 'required|string',
        'new_building_data' => 'required|string',
        'improvement_data' => 'nullable|string',
    ]);

    // Decode JSON data
    $newBuildingData = json_decode($request->new_building_data, true);
    $improvementData = $request->improvement_data ? json_decode($request->improvement_data, true) : [];

    // Validate new building data format
    if (!is_array($newBuildingData)) {
        return back()->withErrors([
            'new_building_data' => 'Invalid data format for new building data.'
        ])->withInput();
    }

    // Validate improvement data format only if provided
    if ($request->improvement_data && !is_array($improvementData)) {
        return back()->withErrors([
            'improvement_data' => 'Invalid data format for improvement data.'
        ])->withInput();
    }

    // Ensure improvement data is an array (even if empty)
    if (!is_array($improvementData)) {
        $improvementData = [];
    }

    // Custom validation for new building data
    $errors = [];
    foreach ($newBuildingData as $index => $data) {
        if (empty($data['name'])) {
            $errors["new_building_data.{$index}.name"] = "Building name is required.";
        }
        if (empty($data['number']) || !is_numeric($data['number'])) {
            $errors["new_building_data.{$index}.number"] = "Number of buildings is required and must be numeric.";
        }
        if (empty($data['cost']) || !is_numeric($data['cost'])) {
            $errors["new_building_data.{$index}.cost"] = "Cost is required and must be numeric.";
        }
        if (empty($data['description'])) {
            $errors["new_building_data.{$index}.description"] = "Description is required.";
        }
    }

    // Custom validation for improvement data (only if provided)
    foreach ($improvementData as $index => $data) {
        if (empty($data['name'])) {
            $errors["improvement_data.{$index}.name"] = "Improvement name is required.";
        }
        if (empty($data['number']) || !is_numeric($data['number'])) {
            $errors["improvement_data.{$index}.number"] = "Number of improvements is required and must be numeric.";
        }
        if (empty($data['cost']) || !is_numeric($data['cost'])) {
            $errors["improvement_data.{$index}.cost"] = "Cost is required and must be numeric.";
        }
        if (empty($data['description'])) {
            $errors["improvement_data.{$index}.description"] = "Description is required.";
        }
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    $user = Auth::user();

    // Get unique institution names from both datasets
    $allInstitutionNames = [];
    
    foreach ($newBuildingData as $building) {
        if (!empty($building['name']) && !in_array($building['name'], $allInstitutionNames)) {
            $allInstitutionNames[] = $building['name'];
        }
    }
    
    foreach ($improvementData as $improvement) {
        if (!empty($improvement['name']) && !in_array($improvement['name'], $allInstitutionNames)) {
            $allInstitutionNames[] = $improvement['name'];
        }
    }

    // If no institutions found, return error
    if (empty($allInstitutionNames)) {
        return back()->withErrors([
            'new_building_data' => 'At least one institution must be selected for new buildings or improvements.'
        ])->withInput();
    }

    $createdCount = 0;
    $skippedCount = 0;

    // For each unique institution, create ONE record
    foreach ($allInstitutionNames as $institutionName) {
        // Find the institution by name
        $institution = Institution::where('name', $institutionName)->first();
        
        if (!$institution) {
            continue; // Skip if institution not found
        }

        // Check if plan already exists for this year, institution, kebele and sector
        $existingPlan = BuildingInstitutionPlan::where('year', $request->year)
            ->where('institution_id', $institution->id)
            ->where('kebele_id', $user->kebele_id)
            // ->where('sector_id', $user->sector_id)
            ->first();

        if ($existingPlan) {
            // SKIP - Institution already has data for this year
            $skippedCount++;
            continue;
        }

        // Filter data for this specific institution
        $institutionNewBuildings = array_filter($newBuildingData, function($building) use ($institutionName) {
            return $building['name'] === $institutionName;
        });
        
        $institutionImprovements = array_filter($improvementData, function($improvement) use ($institutionName) {
            return $improvement['name'] === $institutionName;
        });

        // Reset array keys
        $institutionNewBuildings = array_values($institutionNewBuildings);
        $institutionImprovements = array_values($institutionImprovements);

        // CREATE SINGLE RECORD per institution
        BuildingInstitutionPlan::create([
            'year' => $request->year,
            'institution_id' => $institution->id,
            'new_building_data' => $institutionNewBuildings,
            'improvement_data' => $institutionImprovements,
            // 'sector_id' => $user->sector_id,
            'kebele_id' => $user->kebele_id,
            'woreda_id' => $user->woreda_id,
            'zone_id' => $user->zone_id,
            'region_id' => $user->region_id,
            'created_by' => $user->id,
        ]);

        $createdCount++;
    }

    // Prepare success message
    if ($createdCount > 0 && $skippedCount > 0) {
        $message = "Building plans created successfully! {$createdCount} new plan(s) created and {$skippedCount} institution(s) skipped (data already exists).";
    } elseif ($createdCount > 0) {
        $message = "Building plans created successfully!";
    } else {
        $message = "No new plans were created. All selected institutions already have data for the selected year.";
        return redirect()->route('sector.building-community-institution-plans.index')
            ->with('warning', $message);
    }

    return redirect()->route('sector.building-community-institution-plans.index')
        ->with('success', $message);
}

    /**
     * Merge new building/improvement data with existing data
     * Avoids duplicates by checking building/improvement names
     */
    private function mergeBuildingData(array $existingData, array $newData): array
    {
        $merged = $existingData;

        foreach ($newData as $newItem) {
            $found = false;
            foreach ($merged as &$existingItem) {
                // Check if this building/improvement already exists (by name)
                if ($existingItem['name'] === $newItem['name']) {
                    // Update the existing entry with new values
                    $existingItem['number'] = $newItem['number'];
                    $existingItem['cost'] = $newItem['cost'];
                    $existingItem['description'] = $newItem['description'];
                    $found = true;
                    break;
                }
            }
            
            // If not found, add as new entry
            if (!$found) {
                $merged[] = $newItem;
            }
        }

        return $merged;
    }

    public function checkExisting(Request $request)
    {
        $year = $request->query('year');
        $user = Auth::user();

        $existingPlans = BuildingInstitutionPlan::with('institution')
            ->where('year', $year)
            ->where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
            ->get();

        return response()->json([
            'exists' => $existingPlans->count() > 0,
            'plans' => $existingPlans->map(function($plan) {
                return [
                    'institution_id' => $plan->institution_id,
                    'institution' => [
                        'name' => $plan->institution->name
                    ],
                    'new_building_data' => is_array($plan->new_building_data) 
                        ? $plan->new_building_data 
                        : json_decode($plan->new_building_data, true),
                    'improvement_data' => is_array($plan->improvement_data) 
                        ? $plan->improvement_data 
                        : json_decode($plan->improvement_data, true)
                ];
            })
        ]);
    }

    public function edit($id)
    {
        $plan = BuildingInstitutionPlan::where('kebele_id', Auth::user()->kebele_id)
            ->findOrFail($id);

        // Fetch institutions where institution_type_id = 1
        $institutions = Institution::where('institution_type_id', 1)->get();
            
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        // Decode JSON data for the form
        $plan->new_building_data = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data, true);
        $plan->improvement_data = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data, true);

        return view('sector.building-institutions.edit', compact('plan', 'institutions', 'years'));
    }
public function update(Request $request, $id)
{
    $plan = BuildingInstitutionPlan::findOrFail($id);

    // Validate basic fields
    $validated = $request->validate([
        'year' => 'required|string',
        'institution_id' => 'required|exists:institutions,id',
        'new_building_data' => 'required|string',
        'improvement_data' => 'nullable|string', // Make it nullable
    ]);

    // Decode JSON data
    $newBuildingData = json_decode($request->new_building_data, true);
    $improvementData = $request->improvement_data ? json_decode($request->improvement_data, true) : [];

    // Validate new building data format
    if (!is_array($newBuildingData)) {
        return back()->withErrors([
            'new_building_data' => 'Invalid data format for new building data.'
        ])->withInput();
    }

    // Validate improvement data format only if provided
    if ($request->improvement_data && !is_array($improvementData)) {
        return back()->withErrors([
            'improvement_data' => 'Invalid data format for improvement data.'
        ])->withInput();
    }

    // Ensure improvement data is an array (even if empty)
    if (!is_array($improvementData)) {
        $improvementData = [];
    }

    // Custom validation for new building data
    $errors = [];
    foreach ($newBuildingData as $index => $data) {
        if (empty($data['name'])) {
            $errors["new_building_data.{$index}.name"] = "Building name is required.";
        }
        if (empty($data['number']) || !is_numeric($data['number'])) {
            $errors["new_building_data.{$index}.number"] = "Number of buildings is required and must be numeric.";
        }
        if (empty($data['cost']) || !is_numeric($data['cost'])) {
            $errors["new_building_data.{$index}.cost"] = "Cost is required and must be numeric.";
        }
        if (empty($data['description'])) {
            $errors["new_building_data.{$index}.description"] = "Description is required.";
        }
    }

    // Custom validation for improvement data (only if provided)
    foreach ($improvementData as $index => $data) {
        if (empty($data['name'])) {
            $errors["improvement_data.{$index}.name"] = "Improvement name is required.";
        }
        if (empty($data['number']) || !is_numeric($data['number'])) {
            $errors["improvement_data.{$index}.number"] = "Number of improvements is required and must be numeric.";
        }
        if (empty($data['cost']) || !is_numeric($data['cost'])) {
            $errors["improvement_data.{$index}.cost"] = "Cost is required and must be numeric.";
        }
        if (empty($data['description'])) {
            $errors["improvement_data.{$index}.description"] = "Description is required.";
        }
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    // Update plan
    $plan->update([
        'year' => $request->year,
        'institution_id' => $request->institution_id,
        'new_building_data' => $newBuildingData,
        'improvement_data' => $improvementData,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('sector.building-community-institution-plans.index')
        ->with('success', 'Building community institution plan updated successfully!');
}

    public function destroy($id)
    {
        $plan = BuildingInstitutionPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.building-community-institution-plans.index')
            ->with('success', 'Building community institution plan deleted successfully!');
    }
}