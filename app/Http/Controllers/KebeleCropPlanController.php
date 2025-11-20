<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use Illuminate\Http\Request;
use App\Models\FertilizerType;
use App\Models\Fertilizer;
use App\Models\KebeleCropPlan;
use App\Models\CropConfiguration;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KebeleCropPlanImport;
use App\Imports\KebeleCropPlansImport;
use App\Exports\KebeleCropPlanTemplateExport;


class KebeleCropPlanController extends Controller
{
//   public function index()
// {
//     $user = Auth::user();

//     $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
//         // ->where('sector_id', $user->sector_id)
//         ->where('kebele_id', $user->kebele_id)
//         ->orderBy('year')
//         ->orderBy('season')
//         ->orderBy('croptype_id')
//         ->get();

//     // Calculate yearly totals
//     $yearlyTotals = [];
//     foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
//         $yearlyTotals[$year] = [
//             'total_area' => $plansByYear->sum('area_to_plant'),
//             'total_yield' => $plansByYear->sum('expected_yield'),
//         ];
//     }

//     return view('sector.crop_plans.index', compact('cropPlans', 'yearlyTotals'));
// }
public function index()
{
    $user = Auth::user();

    $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
        ->where('kebele_id', $user->kebele_id)
        ->orderBy('year')
        ->orderBy('season')
        ->orderBy('croptype_id')
        ->get();

    // Calculate totals
    $grandTotalArea = $cropPlans->sum('area_to_plant');
    $grandTotalYield = $cropPlans->sum('expected_yield');

    // Calculate yearly totals
    $yearlyTotals = [];
    foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
        $yearlyTotals[$year] = [
            'total_area' => $plansByYear->sum('area_to_plant'),
            'total_yield' => $plansByYear->sum('expected_yield'),
        ];
    }

    // Get crop types for the productivity comparison
    $cropTypes = \App\Models\CropType::all(); // Adjust this based on your model

    return view('sector.crop_plans.index', compact('cropPlans', 'yearlyTotals', 'grandTotalArea', 'grandTotalYield', 'cropTypes'));
}



public function kebelefertlizer()
{
    $user = Auth::user();

    $cropPlans = KebeleCropPlan::with(['cropType', 'crop'])
        ->where('kebele_id', $user->kebele_id)
        ->orderBy('year', 'desc')
        ->orderBy('croptype_id')
        ->orderBy('crop_id')
        ->get();

    // Group data by Year -> Crop Type -> Crop and calculate fertilizer totals
    $groupedData = [];
    $totalFertilizer = 0;
    $totalArea = 0;
    $fertilizerTypes = [];
    $fertilizers = [];

    // Pre-fetch fertilizer names if possible (adjust based on your actual models)
    try {
        $fertilizerTypes = \App\Models\FertilizerType::pluck('name', 'id')->toArray();
        $fertilizers = \App\Models\Fertilizer::pluck('name', 'id')->toArray();
    } catch (\Exception $e) {
        // If models don't exist, use fallback names
        $fertilizerTypes = [];
        $fertilizers = [];
    }

    foreach ($cropPlans as $plan) {
        $year = $plan->year;
        $cropType = $plan->cropType->name ?? 'Unknown Type';
        $crop = $plan->crop->name ?? 'Unknown Crop';

        // Initialize year if not exists
        if (!isset($groupedData[$year])) {
            $groupedData[$year] = [
                'total_fertilizer' => 0,
                'total_area' => 0,
                'crop_types' => []
            ];
        }

        // Initialize crop type if not exists
        if (!isset($groupedData[$year]['crop_types'][$cropType])) {
            $groupedData[$year]['crop_types'][$cropType] = [
                'total_fertilizer' => 0,
                'total_area' => 0,
                'crops' => []
            ];
        }

        // Initialize crop if not exists
        if (!isset($groupedData[$year]['crop_types'][$cropType]['crops'][$crop])) {
            $groupedData[$year]['crop_types'][$cropType]['crops'][$crop] = [
                'total_fertilizer' => 0,
                'total_area' => 0,
                'fertilizers' => [],
                'plans' => []
            ];
        }

        // Calculate fertilizer details for this plan
        $fertilizerDetails = $plan->fertilizer_details ?? [];
        $planTotalFertilizer = 0;

        if (is_array($fertilizerDetails)) {
            foreach ($fertilizerDetails as $fert) {
                $fertilizerTypeId = $fert['fertilizer_type_id'] ?? null;
                $fertilizerId = $fert['fertilizer_id'] ?? null;
                $amount = floatval($fert['amount'] ?? 0);
                $unit = $fert['unit'] ?? 'kg';

                // Get fertilizer names
                $fertTypeName = $fertilizerTypes[$fertilizerTypeId] ?? "Type {$fertilizerTypeId}";
                $fertName = $fertilizers[$fertilizerId] ?? "Fertilizer {$fertilizerId}";

                // Create a unique key for this fertilizer combination
                $fertKey = $fertilizerTypeId . '_' . $fertilizerId . '_' . $unit;

                // Add to crop's fertilizer summary
                if (!isset($groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['fertilizers'][$fertKey])) {
                    $groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['fertilizers'][$fertKey] = [
                        'fertilizer_type_id' => $fertilizerTypeId,
                        'fertilizer_id' => $fertilizerId,
                        'amount' => 0,
                        'unit' => $unit,
                        'fertilizer_name' => $fertName,
                        'fertilizer_type_name' => $fertTypeName
                    ];
                }

                $groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['fertilizers'][$fertKey]['amount'] += $amount;

                // Only add to total fertilizer if unit is 'kg'
                if ($unit === 'kg') {
                    $planTotalFertilizer += $amount;
                }
            }
        }

        // Update totals
        $area = floatval($plan->area_to_plant);

        $groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['total_fertilizer'] += $planTotalFertilizer;
        $groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['total_area'] += $area;
        $groupedData[$year]['crop_types'][$cropType]['crops'][$crop]['plans'][] = $plan->id;

        $groupedData[$year]['crop_types'][$cropType]['total_fertilizer'] += $planTotalFertilizer;
        $groupedData[$year]['crop_types'][$cropType]['total_area'] += $area;

        $groupedData[$year]['total_fertilizer'] += $planTotalFertilizer;
        $groupedData[$year]['total_area'] += $area;

        $totalFertilizer += $planTotalFertilizer;
        $totalArea += $area;
    }

    // Calculate averages
    $avgPerHectare = $totalArea > 0 ? $totalFertilizer / $totalArea : 0;

    return view('sector.fertilizers.index', compact(
        'groupedData',
        'totalFertilizer',
        'totalArea',
        'avgPerHectare',
        'cropPlans'
    ));
}





        // Optional: AJAX route to fetch crops by crop type

public function getCropTypesByYear($year)
{
    $user = auth()->user();
    $cropTypes = CropConfiguration::where('kebele_id', $user->kebele_id)
        ->where('year', $year)
        ->with('cropType')
        ->get()
        ->pluck('cropType')
        ->unique('id')
        ->values();

    return response()->json($cropTypes);
}

public function getCropsByYearAndType($year, $croptypeId)
{
    $user = auth()->user();
    $configs = CropConfiguration::with('crop')
        ->where('kebele_id', $user->kebele_id)
        ->where('year', $year)
        ->where('croptype_id', $croptypeId)
        ->get();

    $crops = $configs->map(function($config) {
        return [
            'id' => $config->crop->id,
            'name' => $config->crop->name,
            'productivity' => $config->productivity_per_ha,
            'seed_rate' => $config->seed_rate_kg_per_ha,
            'fertilizers' => $config->fertilizer_requirements,
        ];
    });

    return response()->json($crops);
}

 public function create()
{
    $user = Auth::user();
    $kebeleId = $user->kebele_id;

    $fertilizerTypes = FertilizerType::all();

    // ✅ Get distinct years, crop types, and crops available for the user's kebele
    $years = CropConfiguration::where('kebele_id', $kebeleId)
        ->select('year')
        ->distinct()
        ->pluck('year')
        ->sortDesc();

    $cropTypes = CropConfiguration::where('kebele_id', $kebeleId)
        ->with('cropType')
        ->select('croptype_id')
        ->distinct()
        ->get()
        ->pluck('cropType')
        ->filter();

    return view('sector.crop_plans.create', compact('years', 'cropTypes', 'fertilizerTypes'));
}


 public function Quarterlyplan($id)
{
   $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('sector_id', $user->sector_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get(); // load crop types and their crops

        return view('sector.crop_plans.quarteredit', compact('plan', 'cropTypes'));
}

public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'area_to_plant' => 'required|numeric|min:0',
        'productivity' => 'required|numeric|min:0',
        'year' => 'required|integer|min:1',
        'season' => 'required|string|max:255',
        'seed_required' => 'required|numeric|min:0',
    ]);

    // Check for duplicate crop plan
    $existingPlan = KebeleCropPlan::with(['crop', 'cropType'])
        ->where('kebele_id', $user->kebele_id)
        ->where('crop_id', $validated['crop_id'])
        ->where('year', $validated['year'])
        ->where('season', $validated['season'])
        ->first();

    if ($existingPlan) {
        $cropName = $existingPlan->crop->name ?? 'Unknown Crop';
        $cropType = $existingPlan->cropType->name ?? 'Unknown Type';

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'crop_id' => "A crop plan for \"{$cropName}\" ({$cropType}) in {$validated['year']} {$validated['season']} season already exists. "
            ]);
    }

    // Collect all fertilizer data
    $fertilizerDetails = $this->collectFertilizerDetails($request);
    $fertilizerTypeIds = $request->input('fertilizer_types', []);

    $area = $validated['area_to_plant'];
    $productivity = $validated['productivity'];
    $expectedYield = $area * $productivity;

    KebeleCropPlan::create([
        'kebele_id' => $user->kebele_id,
        'sector_id' => $user->sector_id,
        'croptype_id' => $validated['croptype_id'],
        'crop_id' => $validated['crop_id'],
        'area_to_plant' => $area,
        'expected_yield' => $expectedYield,
        'year' => $validated['year'],
        'season' => $validated['season'],
        'seed_required' => $validated['seed_required'],
        'fertilizer_type_id' => $fertilizerTypeIds,
        'fertilizer_details' => $fertilizerDetails,
        'responsible_staff' => $request->responsible_staff,
        'notes' => $request->notes,
        'productivity' => $productivity,
        'created_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plan added successfully!');
}


private function collectFertilizerDetails(Request $request)
{
    $details = [];

    if ($request->has('fertilizer_types')) {
        foreach ($request->fertilizer_types as $typeId) {
            if ($request->has("fertilizers.$typeId")) {
                $fertilizers = $request->input("fertilizers.$typeId", []);
                $amounts = $request->input("amount.$typeId", []);
                $units = $request->input("unit.$typeId", []);

                foreach ($fertilizers as $index => $fertilizerId) {
                    $details[] = [
                        'fertilizer_type_id' => $typeId,
                        'fertilizer_id' => $fertilizerId,
                        'amount' => $amounts[$index] ?? 0,
                        'unit' => $units[$index] ?? 'kg',
                    ];
                }
            }
        }
    }

    return $details;
}




public function getFertilizers($typeId)
{
    return response()->json(Fertilizer::where('fertilizer_type_id', $typeId)->get());
}







    public function edit($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get(); // load crop types and their crops

        return view('sector.crop_plans.edit', compact('plan', 'cropTypes'));
    }

     public function activityplan($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get(); // load crop types and their crops

        return view('sector.crop_plans.activityplan', compact('plan', 'cropTypes'));
    }



 public function update(Request $request, $id)
{
    $user = Auth::user();
    $cropPlan = KebeleCropPlan::findOrFail($id);

    $validated = $request->validate([
        'croptype_id' => 'required|exists:crop_types,id',
        'crop_id' => 'required|exists:crops,id',
        'area_to_plant' => 'required|numeric|min:0',
        'productivity' => 'required|numeric|min:0',
        'year' => 'required|integer|min:1',
        'season' => 'required|string|max:255',
        'seed_required' => 'required|numeric|min:0',
        'plandivision_details' => 'sometimes|json',

    ]);

    // Check for duplicate crop plan (excluding current record)
    $existingPlan = KebeleCropPlan::where('kebele_id', $user->kebele_id)
        ->where('crop_id', $validated['crop_id'])
        ->where('year', $validated['year'])
        ->where('season', $validated['season'])
        ->where('id', '!=', $id)
        ->first();

    if ($existingPlan) {
        $cropName = $existingPlan->crop->name ?? 'Unknown Crop';
        $cropType = $existingPlan->cropType->name ?? 'Unknown Type';

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'crop_id' => "A crop plan for \"{$cropName}\" ({$cropType}) in {$validated['year']} {$validated['season']} season already exists. " .
                           "Please choose a different crop, year, or season."
            ]);
    }

    // Collect all fertilizer data
    $fertilizerDetails = $this->collectFertilizerDetails($request);
    $fertilizerTypeIds = $request->input('fertilizer_types', []);

    $area = $validated['area_to_plant'];
    $productivity = $validated['productivity'];
    $expectedYield = $area * $productivity;

    // Parse planning division details
    $planDivisionDetails = json_decode($request->plandivision_details, true) ?? [];
    $yieldTimelineDetails = json_decode($request->yield_timeline_details, true) ?? [];

    $cropPlan->update([
        'croptype_id' => $validated['croptype_id'],
        'crop_id' => $validated['crop_id'],
        'area_to_plant' => $area,
        'expected_yield' => $expectedYield,
        'year' => $validated['year'],
        'season' => $validated['season'],
        'seed_required' => $validated['seed_required'],
        'fertilizer_type_id' => $fertilizerTypeIds,
        'fertilizer_details' => $fertilizerDetails,
        'plandivision_details' => $planDivisionDetails,
        'responsible_staff' => $request->responsible_staff,
        'notes' => $request->notes,
        'productivity' => $productivity,
        'updated_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plan updated successfully!');
}


    public function plantime(Request $request)
    {
        $user = Auth::user();

        // Get crop plans for the user's kebele
        $cropPlans = KebeleCropPlan::where('kebele_id', $user->kebele_id)
            ->with(['cropType', 'crop'])
            ->orderBy('year', 'desc')
            ->orderBy('season')
            ->get();

        // Transform data for the Excel-like display
        $transformedPlans = $cropPlans->map(function ($plan) {
            $plandivisionDetails = $plan->plandivision_details ?? [];

            // Extract timeline data from plandivision_details
            $timelineData = $this->extractTimelineData($plandivisionDetails, $plan->year);

            return [
                'id' => $plan->id,
                'crop_type' => $plan->cropType->name ?? 'N/A',
                'crop_name' => $plan->crop->name ?? 'N/A',
                'unit' => 'Hectare',
                'previous_year' => $this->getPreviousYearData($plan),
                'plandivision_details' => $plandivisionDetails,
                'timeline_data' => $timelineData, // Extracted timeline data
                'area_to_plant' => $plan->area_to_plant,
                'expected_yield' => $plan->expected_yield,
                'year' => $plan->year,
                'season' => $plan->season,
                'seed_required' => $plan->seed_required,
                'responsible_staff' => $plan->responsible_staff,
                'notes' => $plan->notes,
                'updated_at' => $plan->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'plans' => $transformedPlans,
                'total' => $transformedPlans->count()
            ]);
        }

        return view('sector.crop_plans.plantime', compact('cropPlans', 'transformedPlans'));
    }

    /**
     * Extract timeline data from plandivision_details
     */
    private function extractTimelineData($plandivisionDetails, $planYear)
    {
        $timelineData = [
            'preparation_year' => $planYear - 1, // Default fallback
            'quarters' => [],
            'months' => [],
            'weeks' => [],
            'total_distributed_area' => 0,
            'total_distributed_yield' => 0
        ];

        if (empty($plandivisionDetails)) {
            return $timelineData;
        }

        // Handle different possible structures of plandivision_details
        if (is_array($plandivisionDetails)) {
            // Check for preparation_year in the data
            if (isset($plandivisionDetails['preparation_year'])) {
                $timelineData['preparation_year'] = $plandivisionDetails['preparation_year'];
            }

            // Structure 1: Direct array with quarters, months, weeks
            if (isset($plandivisionDetails['quarters'])) {
                $timelineData = array_merge($timelineData, $this->extractFromStructuredData($plandivisionDetails));
            }
            // Structure 2: Array of weekly distributions
            else if (isset($plandivisionDetails[0]) && is_array($plandivisionDetails[0])) {
                $timelineData = array_merge($timelineData, $this->extractFromWeeklyDistribution($plandivisionDetails));
            }
            // Structure 3: Legacy format with nested structure
            else if (isset($plandivisionDetails['weekly_distribution'])) {
                $timelineData = array_merge($timelineData, $this->extractFromWeeklyDistribution($plandivisionDetails['weekly_distribution']));
            }

            // Also check for preparation_year in nested structures
            if (isset($plandivisionDetails['preparation_year'])) {
                $timelineData['preparation_year'] = $plandivisionDetails['preparation_year'];
            }
        }

        return $timelineData;
    }

    /**
     * Extract from structured data with quarters, months, weeks
     */
    private function extractFromStructuredData($data)
    {
        $timelineData = [
            'quarters' => [],
            'months' => [],
            'weeks' => [],
            'total_distributed_area' => 0,
            'total_distributed_yield' => 0
        ];

        // Check for preparation_year in structured data
        if (isset($data['preparation_year'])) {
            $timelineData['preparation_year'] = $data['preparation_year'];
        }

        // Extract quarters data
        if (isset($data['quarters']) && is_array($data['quarters'])) {
            foreach ($data['quarters'] as $quarterName => $quarterData) {
                if (isset($quarterData['area']) && $quarterData['area'] > 0) {
                    $timelineData['quarters'][$quarterName] = [
                        'area' => $quarterData['area'],
                        'yield' => $quarterData['yield'] ?? 0,
                        'checked' => $quarterData['checked'] ?? false
                    ];
                    $timelineData['total_distributed_area'] += $quarterData['area'];
                    $timelineData['total_distributed_yield'] += $quarterData['yield'] ?? 0;
                }
            }
        }

        // Extract months data
        if (isset($data['months']) && is_array($data['months'])) {
            foreach ($data['months'] as $monthName => $monthData) {
                if (isset($monthData['area']) && $monthData['area'] > 0) {
                    $timelineData['months'][$monthName] = [
                        'area' => $monthData['area'],
                        'yield' => $monthData['yield'] ?? 0,
                        'quarter' => $monthData['quarter'] ?? 'Unknown',
                        'checked' => $monthData['checked'] ?? false
                    ];
                }
            }
        }

        // Extract weeks data
        if (isset($data['weeks']) && is_array($data['weeks'])) {
            foreach ($data['weeks'] as $weekKey => $weekData) {
                if (isset($weekData['area']) && $weekData['area'] > 0) {
                    $timelineData['weeks'][$weekKey] = [
                        'area' => $weekData['area'],
                        'yield' => $weekData['yield'] ?? 0,
                        'month' => $weekData['month'] ?? 'Unknown',
                        'quarter' => $weekData['quarter'] ?? 'Unknown',
                        'week_number' => $weekData['week_number'] ?? 1,
                        'checked' => $weekData['checked'] ?? false
                    ];
                }
            }
        }

        return $timelineData;
    }

    /**
     * Extract from weekly distribution array
     */
    private function extractFromWeeklyDistribution($weeklyDistribution)
    {
        $timelineData = [
            'quarters' => [],
            'months' => [],
            'weeks' => [],
            'total_distributed_area' => 0,
            'total_distributed_yield' => 0
        ];

        foreach ($weeklyDistribution as $week) {
            if (is_array($week)) {
                $area = $week['area'] ?? 0;
                $yield = $week['yield'] ?? 0;
                $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
                $month = $week['month'] ?? 'Unknown';
                $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);

                // Check for preparation_year in weekly data
                if (isset($week['preparation_year'])) {
                    $timelineData['preparation_year'] = $week['preparation_year'];
                }

                if ($area > 0) {
                    // Add to total
                    $timelineData['total_distributed_area'] += $area;
                    $timelineData['total_distributed_yield'] += $yield;

                    // Track quarter
                    if (!isset($timelineData['quarters'][$quarter])) {
                        $timelineData['quarters'][$quarter] = ['area' => 0, 'yield' => 0];
                    }
                    $timelineData['quarters'][$quarter]['area'] += $area;
                    $timelineData['quarters'][$quarter]['yield'] += $yield;

                    // Track month
                    if (!isset($timelineData['months'][$month])) {
                        $timelineData['months'][$month] = ['area' => 0, 'yield' => 0, 'quarter' => $quarter];
                    }
                    $timelineData['months'][$month]['area'] += $area;
                    $timelineData['months'][$month]['yield'] += $yield;

                    // Track week
                    $weekKey = $month . '_week_' . $weekNumber;
                    $timelineData['weeks'][$weekKey] = [
                        'area' => $area,
                        'yield' => $yield,
                        'month' => $month,
                        'quarter' => $quarter,
                        'week_number' => $weekNumber
                    ];
                }
            }
        }

        return $timelineData;
    }

    /**
     * Get previous year data for a crop plan
     */
    private function getPreviousYearData($plan)
    {
        // Implement logic to get previous year's data
        // This is a placeholder - adjust based on your data structure
        $previousYearPlan = KebeleCropPlan::where('kebele_id', $plan->kebele_id)
            ->where('crop_id', $plan->crop_id)
            ->where('year', $plan->year - 1)
            ->first();

        return $previousYearPlan ? $previousYearPlan->area_to_plant : 0;
    }

    /**
     * Export crop plans to Excel
     */
    public function exportExcel()
    {
        $user = Auth::user();

        $cropPlans = KebeleCropPlan::where('kebele_id', $user->kebele_id)
            ->with(['cropType', 'crop'])
            ->orderBy('year', 'desc')
            ->orderBy('season')
            ->get();

        // You would typically use a package like Maatwebsite/Laravel-Excel here
        // This is a simplified version
        $fileName = 'crop_plans_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($cropPlans) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Crop Type', 'Crop Name', 'Area to Plant', 'Expected Yield',
                'Year', 'Season', 'Seed Required', 'Responsible Staff'
            ]);

            // Data rows
            foreach ($cropPlans as $plan) {
                fputcsv($file, [
                    $plan->cropType->name ?? 'N/A',
                    $plan->crop->name ?? 'N/A',
                    $plan->area_to_plant,
                    $plan->expected_yield,
                    $plan->year,
                    $plan->season,
                    $plan->seed_required,
                    $plan->responsible_staff ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }




  public function editfertilizer($id)
{
    $cropPlan = KebeleCropPlan::findOrFail($id);
    $fertilizerTypes = \App\Models\FertilizerType::all();
    $fertilizers = \App\Models\Fertilizer::all();

    return view('sector.crop_plans.edit_fertilizer', compact('cropPlan', 'fertilizerTypes', 'fertilizers'));
}

public function updatefertilizer(Request $request, $id)
{
    $cropPlan = KebeleCropPlan::findOrFail($id);

    $validated = $request->validate([
        'fertilizer_types' => 'nullable|array',
        'fertilizer_types.*' => 'exists:fertilizer_types,id',
        'fertilizer_details' => 'nullable|array',
    ]);

    // Clean up fertilizer_details - remove empty entries
    $fertilizerDetails = [];
    if (!empty($validated['fertilizer_details'])) {
        foreach ($validated['fertilizer_details'] as $typeId => $fertilizers) {
            foreach ($fertilizers as $fertId => $detail) {
                // Only include if amount is provided
                if (!empty($detail['amount']) && !empty($detail['unit'])) {
                    $fertilizerDetails[] = [
                        'fertilizer_type_id' => $typeId,
                        'fertilizer_id' => $fertId,
                        'amount' => $detail['amount'],
                        'unit' => $detail['unit']
                    ];
                }
            }
        }
    }

    $cropPlan->fertilizer_type_id = $validated['fertilizer_types'] ?? [];
    $cropPlan->fertilizer_details = $fertilizerDetails;

    $cropPlan->save();

    return redirect()->route('sector.fertlizer-plans.index')
        ->with('success', 'Fertilizer details updated successfully.');
}





    public function destroy($id)
    {
        $user = Auth::user();
        $plan = KebeleCropPlan::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $plan->delete();

        return back()->with('success', 'Crop plan deleted successfully!');

    }




public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    Excel::import(new KebeleCropPlansImport, $request->file('file'));

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Crop plans imported successfully!');
}




public function downloadTemplate()
{
    return Excel::download(new KebeleCropPlanTemplateExport, 'kebele_crop_plan_template.xlsx');
}




public function showImportForm()
{
    return view('sector.crop_plans.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    Excel::import(new KebeleCropPlanImport, $request->file('file'));

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plans imported successfully!');
}



}
