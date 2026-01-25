<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EconomicTransitionFacility;

class BuildingEconomicInstitutionPlanController extends Controller
{
   public function index()
    {
        $plans = EconomicTransitionFacility::with(['institution', 'sector', 'kebele', 'woreda', 'zone', 'region', 'createdBy'])
            ->where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($plan) {
                $plan->new_building_data = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data, true);
                $plan->improvement_data = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data, true);
                return $plan;
            });

        return view('sector.economic-institutions.index', compact('plans'));
    }

    public function create()
    {
        $institutions = Institution::where('institution_type_id', 3)->get();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.economic-institutions.create', compact('institutions', 'years'));
    }

   public function store(Request $request)
{
    // Validate only year as required, all others nullable
    $validated = $request->validate([
        'year' => 'required|string',
        'new_building_data' => 'nullable',
        'improvement_data' => 'nullable',
    ]);

    // Initialize empty arrays
    $newBuildingData = [];
    $improvementData = [];

    // Decode JSON data only if provided and valid
    if ($request->filled('new_building_data')) {
        $decoded = json_decode($request->new_building_data, true);
        if (is_array($decoded)) {
            $newBuildingData = $decoded;
        } else {
            return back()->withErrors([
                'new_building_data' => 'Invalid data format for new building data.'
            ])->withInput();
        }
    }

    if ($request->filled('improvement_data')) {
        $decoded = json_decode($request->improvement_data, true);
        if (is_array($decoded)) {
            $improvementData = $decoded;
        } else {
            return back()->withErrors([
                'improvement_data' => 'Invalid data format for improvement data.'
            ])->withInput();
        }
    }

    // Custom validation for new building data (only if provided)
    $errors = [];
    
    // Validate new building data if provided
    if (!empty($newBuildingData)) {
        foreach ($newBuildingData as $index => $data) {
            // Only institution name is required
            if (empty($data['name'])) {
                $errors["new_building_data.{$index}.name"] = "Institution name is required.";
            }
            
            // All other fields are optional - only validate if they're provided and not empty
            if (isset($data['public_number']) && $data['public_number'] !== '' && !is_numeric($data['public_number'])) {
                $errors["new_building_data.{$index}.public_number"] = "Public number of buildings must be numeric if provided.";
            }
            
            if (isset($data['private_number']) && $data['private_number'] !== '' && !is_numeric($data['private_number'])) {
                $errors["new_building_data.{$index}.private_number"] = "Private number of buildings must be numeric if provided.";
            }
            
            if (isset($data['association_number']) && $data['association_number'] !== '' && !is_numeric($data['association_number'])) {
                $errors["new_building_data.{$index}.association_number"] = "Association number of buildings must be numeric if provided.";
            }
            
            if (isset($data['public_cost']) && $data['public_cost'] !== '' && !is_numeric($data['public_cost'])) {
                $errors["new_building_data.{$index}.public_cost"] = "Public cost must be numeric if provided.";
            }
            
            if (isset($data['private_cost']) && $data['private_cost'] !== '' && !is_numeric($data['private_cost'])) {
                $errors["new_building_data.{$index}.private_cost"] = "Private cost must be numeric if provided.";
            }
            
            if (isset($data['association_cost']) && $data['association_cost'] !== '' && !is_numeric($data['association_cost'])) {
                $errors["new_building_data.{$index}.association_cost"] = "Association cost must be numeric if provided.";
            }
            
            // Description is optional - no validation needed
        }
    }

    // Custom validation for improvement data (only if provided)
    if (!empty($improvementData)) {
        foreach ($improvementData as $index => $data) {
            // Only institution name is required
            if (empty($data['name'])) {
                $errors["improvement_data.{$index}.name"] = "Institution name is required.";
            }
            
            // All other fields are optional - only validate if they're provided and not empty
            if (isset($data['public_number']) && $data['public_number'] !== '' && !is_numeric($data['public_number'])) {
                $errors["improvement_data.{$index}.public_number"] = "Public number of improvements must be numeric if provided.";
            }
            
            if (isset($data['private_number']) && $data['private_number'] !== '' && !is_numeric($data['private_number'])) {
                $errors["improvement_data.{$index}.private_number"] = "Private number of improvements must be numeric if provided.";
            }
            
            if (isset($data['association_number']) && $data['association_number'] !== '' && !is_numeric($data['association_number'])) {
                $errors["improvement_data.{$index}.association_number"] = "Association number of improvements must be numeric if provided.";
            }
            
            if (isset($data['public_cost']) && $data['public_cost'] !== '' && !is_numeric($data['public_cost'])) {
                $errors["improvement_data.{$index}.public_cost"] = "Public cost must be numeric if provided.";
            }
            
            if (isset($data['private_cost']) && $data['private_cost'] !== '' && !is_numeric($data['private_cost'])) {
                $errors["improvement_data.{$index}.private_cost"] = "Private cost must be numeric if provided.";
            }
            
            if (isset($data['association_cost']) && $data['association_cost'] !== '' && !is_numeric($data['association_cost'])) {
                $errors["improvement_data.{$index}.association_cost"] = "Association cost must be numeric if provided.";
            }
            
            // Description is optional - no validation needed
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

    // If no institutions found in the data, return error
    if (empty($allInstitutionNames)) {
        return back()->withErrors([
            'new_building_data' => 'At least one institution must be selected.'
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
        $existingPlan = EconomicTransitionFacility::where('year', $request->year)
            ->where('institution_id', $institution->id)
            ->where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
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

        // Create record with all data (including empty/optional fields)
        EconomicTransitionFacility::create([
            'year' => $request->year,
            'institution_id' => $institution->id,
            'new_building_data' => $institutionNewBuildings,
            'improvement_data' => $institutionImprovements,
            'sector_id' => $user->sector_id,
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
        return redirect()->route('sector.building-economic-institution-plans.index')
            ->with('warning', $message);
    }

    return redirect()->route('sector.building-economic-institution-plans.index')
        ->with('success', $message);
}

    public function checkExisting(Request $request)
    {
        $year = $request->query('year');
        $user = Auth::user();

        $existingPlans = EconomicTransitionFacility::with('institution')
            ->where('year', $year)
            ->where('kebele_id', $user->kebele_id)
            // ->where('sector_id', $user->sector_id)
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
    $plan = EconomicTransitionFacility::with('institution')
        ->where('kebele_id', Auth::user()->kebele_id)
        ->findOrFail($id);

    $institutions = Institution::where('institution_type_id', 3)->get(); // Make sure this matches your institution type
            
    $currentYear = \App\Helpers\EthiopianYear::now();
    $years = range($currentYear - 2, $currentYear + 3);

    // Decode JSON data for the form
    $plan->new_building_data = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data, true);
    $plan->improvement_data = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data, true);

    return view('sector.economic-institutions.edit', compact('plan', 'institutions', 'years'));
}

    public function update(Request $request, $id)
    {
        $plan = EconomicTransitionFacility::findOrFail($id);

        // Validate basic fields
        $validated = $request->validate([
            'year' => 'required|string',
            'institution_id' => 'required|exists:institutions,id',
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
            if (empty($data['public_number']) || !is_numeric($data['public_number'])) {
                $errors["new_building_data.{$index}.public_number"] = "Public number of buildings is required and must be numeric.";
            }
            if (empty($data['private_number']) || !is_numeric($data['private_number'])) {
                $errors["new_building_data.{$index}.private_number"] = "Private number of buildings is required and must be numeric.";
            }
            if (empty($data['association_number']) || !is_numeric($data['association_number'])) {
                $errors["new_building_data.{$index}.association_number"] = "association number of buildings is required and must be numeric.";
            }
            
            if (empty($data['public_cost']) || !is_numeric($data['public_cost'])) {
                $errors["new_building_data.{$index}.public_cost"] = "Public cost is required and must be numeric.";
            }
            if (empty($data['private_cost']) || !is_numeric($data['private_cost'])) {
                $errors["new_building_data.{$index}.private_cost"] = "Private cost is required and must be numeric.";
            }
             if (empty($data['association_cost']) || !is_numeric($data['association_cost'])) {
                $errors["new_building_data.{$index}.association_cost"] = "association cost is required and must be numeric.";
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
            if (empty($data['public_number']) || !is_numeric($data['public_number'])) {
                $errors["improvement_data.{$index}.public_number"] = "Public number of improvements is required and must be numeric.";
            }
            if (empty($data['private_number']) || !is_numeric($data['private_number'])) {
                $errors["improvement_data.{$index}.private_number"] = "Private number of improvements is required and must be numeric.";
            }
             if (empty($data['association_number']) || !is_numeric($data['association_number'])) {
                $errors["improvement_data.{$index}.association_number"] = "association number of improvements is required and must be numeric.";
            }
            
            if (empty($data['public_cost']) || !is_numeric($data['public_cost'])) {
                $errors["improvement_data.{$index}.public_cost"] = "Public cost is required and must be numeric.";
            }
            if (empty($data['private_cost']) || !is_numeric($data['private_cost'])) {
                $errors["improvement_data.{$index}.private_cost"] = "Private cost is required and must be numeric.";
            }
             if (empty($data['association_cost']) || !is_numeric($data['association_cost'])) {
                $errors["improvement_data.{$index}.association_cost"] = "association cost is required and must be numeric.";
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

        return redirect()->route('sector.building-economic-institution-plans.index')
            ->with('success', 'Building power institution plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = EconomicTransitionFacility::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.building-economic-institution-plans.index')
            ->with('success', 'Building power institution plan deleted successfully!');
    }
}
