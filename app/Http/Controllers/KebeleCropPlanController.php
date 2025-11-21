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
        ->orderBy('season')
        ->orderBy('croptype_id')
        ->orderBy('crop_id')
        ->get();

    // Group data by Year -> Season -> Crop Type
    $groupedData = [];
    $totalFertilizerByUnit = []; // Track totals by unit
    $totalArea = 0;

    // Calculate year totals first
    $yearTotals = [];
    $yearFertilizerByUnit = []; // Track year totals by unit
    
    foreach ($cropPlans as $plan) {
        $year = $plan->year;
        $area = floatval($plan->area_to_plant);
        
        if (!isset($yearTotals[$year])) {
            $yearTotals[$year] = [
                'total_area' => 0,
                'fertilizer_by_unit' => []
            ];
        }
        $yearTotals[$year]['total_area'] += $area;
    }

    // Fetch fertilizer names from database
    try {
        $fertilizerTypes = \App\Models\FertilizerType::pluck('name', 'id')->toArray();
        $fertilizers = \App\Models\Fertilizer::pluck('name', 'id')->toArray();
    } catch (\Exception $e) {
        // Fallback if models don't exist
        $fertilizerTypes = [];
        $fertilizers = [];
    }

    foreach ($cropPlans as $plan) {
        $year = $plan->year;
        $season = $plan->season;
        $cropType = $plan->cropType->name ?? 'Unknown Type';
        $crop = $plan->crop->name ?? 'Unknown Crop';

        // Create unique key for year and season combination
        $yearSeasonKey = $year . ' - ' . $season;

        // Initialize year-season if not exists
        if (!isset($groupedData[$yearSeasonKey])) {
            $groupedData[$yearSeasonKey] = [
                'year' => $year,
                'season' => $season,
                'total_area' => 0,
                'fertilizer_by_unit' => [], // Track fertilizer by unit
                'crop_types' => []
            ];
        }

        // Initialize crop type if not exists
        if (!isset($groupedData[$yearSeasonKey]['crop_types'][$cropType])) {
            $groupedData[$yearSeasonKey]['crop_types'][$cropType] = [
                'total_area' => 0,
                'fertilizer_by_unit' => [], // Track fertilizer by unit
                'crops' => [],
                'fertilizers' => []
            ];
        }

        // Add individual crop to the crop type
        $cropKey = $crop . ' (Plan: ' . $plan->id . ')';
        
        $groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey] = [
            'crop_name' => $crop,
            'plan_id' => $plan->id,
            'area' => floatval($plan->area_to_plant),
            'fertilizers' => [],
            'fertilizer_by_unit' => [] // Track fertilizer by unit for each crop
        ];

        // Calculate fertilizer details for this plan
        $fertilizerDetails = $plan->fertilizer_details ?? [];
        $planFertilizerByUnit = []; // Track fertilizer by unit for this plan

        if (is_array($fertilizerDetails)) {
            foreach ($fertilizerDetails as $fert) {
                $fertilizerTypeId = $fert['fertilizer_type_id'] ?? null;
                $fertilizerId = $fert['fertilizer_id'] ?? null;
                $amount = floatval($fert['amount'] ?? 0);
                $unit = $fert['unit'] ?? 'kg';

                // Get actual fertilizer names
                $fertTypeName = $fertilizerTypes[$fertilizerTypeId] ?? "Type #{$fertilizerTypeId}";
                $fertName = $fertilizers[$fertilizerId] ?? "Fertilizer #{$fertilizerId}";

                // Create a unique key for this fertilizer combination
                $fertKey = $fertilizerTypeId . '_' . $fertilizerId . '_' . $unit;

                // Add to crop's fertilizer summary
                if (!isset($groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizers'][$fertKey])) {
                    $groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizers'][$fertKey] = [
                        'fertilizer_type_id' => $fertilizerTypeId,
                        'fertilizer_id' => $fertilizerId,
                        'amount' => 0,
                        'unit' => $unit,
                        'fertilizer_name' => $fertName,
                        'fertilizer_type_name' => $fertTypeName
                    ];
                }

                $groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizers'][$fertKey]['amount'] += $amount;

                // Add to crop's fertilizer by unit
                if (!isset($groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizer_by_unit'][$unit])) {
                    $groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizer_by_unit'][$unit] = 0;
                }
                $groupedData[$yearSeasonKey]['crop_types'][$cropType]['crops'][$cropKey]['fertilizer_by_unit'][$unit] += $amount;

                // Add to crop type's fertilizer summary
                if (!isset($groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizers'][$fertKey])) {
                    $groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizers'][$fertKey] = [
                        'fertilizer_type_id' => $fertilizerTypeId,
                        'fertilizer_id' => $fertilizerId,
                        'amount' => 0,
                        'unit' => $unit,
                        'fertilizer_name' => $fertName,
                        'fertilizer_type_name' => $fertTypeName
                    ];
                }

                $groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizers'][$fertKey]['amount'] += $amount;

                // Add to crop type's fertilizer by unit
                if (!isset($groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizer_by_unit'][$unit])) {
                    $groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizer_by_unit'][$unit] = 0;
                }
                $groupedData[$yearSeasonKey]['crop_types'][$cropType]['fertilizer_by_unit'][$unit] += $amount;

                // Add to year-season's fertilizer by unit
                if (!isset($groupedData[$yearSeasonKey]['fertilizer_by_unit'][$unit])) {
                    $groupedData[$yearSeasonKey]['fertilizer_by_unit'][$unit] = 0;
                }
                $groupedData[$yearSeasonKey]['fertilizer_by_unit'][$unit] += $amount;

                // Add to year's fertilizer by unit
                if (!isset($yearTotals[$year]['fertilizer_by_unit'][$unit])) {
                    $yearTotals[$year]['fertilizer_by_unit'][$unit] = 0;
                }
                $yearTotals[$year]['fertilizer_by_unit'][$unit] += $amount;

                // Add to grand total by unit
                if (!isset($totalFertilizerByUnit[$unit])) {
                    $totalFertilizerByUnit[$unit] = 0;
                }
                $totalFertilizerByUnit[$unit] += $amount;

                // Track for this plan
                if (!isset($planFertilizerByUnit[$unit])) {
                    $planFertilizerByUnit[$unit] = 0;
                }
                $planFertilizerByUnit[$unit] += $amount;
            }
        }

        // Update totals
        $area = floatval($plan->area_to_plant);

        // Update crop type totals
        $groupedData[$yearSeasonKey]['crop_types'][$cropType]['total_area'] += $area;

        // Update year-season totals
        $groupedData[$yearSeasonKey]['total_area'] += $area;

        // Update grand totals
        $totalArea += $area;
    }

    // Add year totals to grouped data for display
    foreach ($groupedData as $yearSeasonKey => $yearSeasonData) {
        $year = $yearSeasonData['year'];
        $groupedData[$yearSeasonKey]['year_total_area'] = $yearTotals[$year]['total_area'] ?? 0;
        $groupedData[$yearSeasonKey]['year_fertilizer_by_unit'] = $yearTotals[$year]['fertilizer_by_unit'] ?? [];
    }

    // Calculate averages (using kg for consistency, or you can calculate per unit)
    $totalKg = $totalFertilizerByUnit['kg'] ?? 0;
    $avgPerHectare = $totalArea > 0 ? $totalKg / $totalArea : 0;

    return view('sector.fertilizers.index', compact(
        'groupedData',
        'totalFertilizerByUnit',
        'totalArea',
        'avgPerHectare',
        'cropPlans',
        'yearTotals'
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

    $cropTypes = CropType::with('crops')->get();

    // Transform the single plan data for the table structure
    $transformedPlans = $this->transformPlanDataForTable($plan);

    return view('sector.crop_plans.activityplan', compact('plan', 'cropTypes', 'transformedPlans'));
}


private function transformPlanDataForTable($plan)
{
    // Extract timeline data from plan division details
    $planDivisionDetails = $plan->plandivision_details ?? [];
    
    // Initialize timeline data structure
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_area' => 0,
        'preparation_year' => $plan->year - 1 // Assuming preparation year is previous year
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process plan division details to extract timeline data
    if (!empty($planDivisionDetails) && is_array($planDivisionDetails)) {
        // Handle both array and object formats
        $weeklyDistributions = [];
        
        if (isset($planDivisionDetails['weekly_distribution']) && is_array($planDivisionDetails['weekly_distribution'])) {
            $weeklyDistributions = $planDivisionDetails['weekly_distribution'];
        } elseif (is_array($planDivisionDetails) && !isset($planDivisionDetails['weekly_distribution'])) {
            $weeklyDistributions = $planDivisionDetails;
        }

        foreach ($weeklyDistributions as $distribution) {
            if (isset($distribution['area']) && $distribution['area'] > 0) {
                $area = floatval($distribution['area']);
                $quarter = $distribution['quarter'] ?? '';
                $month = $distribution['month'] ?? '';
                $week = $distribution['week'] ?? '';

                // Add to total distributed area
                $timelineData['total_distributed_area'] += $area;

                // Add to quarter totals
                if ($quarter && isset($ethiopianMonths[$quarter])) {
                    if (!isset($timelineData['quarters'][$quarter])) {
                        $timelineData['quarters'][$quarter] = ['area' => 0];
                    }
                    $timelineData['quarters'][$quarter]['area'] += $area;
                }

                // Add to month totals
                if ($month) {
                    if (!isset($timelineData['months'][$month])) {
                        $timelineData['months'][$month] = ['area' => 0];
                    }
                    $timelineData['months'][$month]['area'] += $area;
                }

                // Add to week totals
                if ($week && $month) {
                    $weekKey = $month . ' ' . $week;
                    if (!isset($timelineData['weeks'][$weekKey])) {
                        $timelineData['weeks'][$weekKey] = ['area' => 0];
                    }
                    $timelineData['weeks'][$weekKey]['area'] += $area;
                }
            }
        }
    }

    // Create the transformed plan structure
    return [
        [
            'year' => $plan->year,
            'crop_type' => $plan->cropType->name ?? 'Unknown',
            'crop_name' => $plan->crop->name ?? 'Unknown',
            'unit' => 'ha',
            'area_to_plant' => floatval($plan->area_to_plant),
            'season' => $plan->season,
            'timeline_data' => $timelineData
        ]
    ];
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
        'productionplan_details' => 'sometimes|json',
        

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
    $productionplanDetails = json_decode($request->productionplan_details, true) ?? [];
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
        'productionplan_details' => $productionplanDetails,
        
        'responsible_staff' => $request->responsible_staff,
        'notes' => $request->notes,
        'productivity' => $productivity,
        'updated_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Kebele Crop Plan updated successfully!');
}



// production division 

public function productionactivityplan($id)
{
    $user = Auth::user();

    // Retrieve the plan; ensure user can only edit plans in their sector
    $plan = KebeleCropPlan::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    $cropTypes = CropType::with('crops')->get();

    // Transform the single plan data for production planning table structure
    $transformedPlans = $this->transformPlanDataForProductionTable($plan);

    return view('sector.crop_plans.production_activityplan', compact('plan', 'cropTypes', 'transformedPlans'));
}

private function transformPlanDataForProductionTable($plan)
{
    // Extract production timeline data from plan details
    $productionDetails = $plan->productionplan_details ?? [];
    
    // Initialize production timeline data structure
    $productionData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_yield' => 0,
        'production_year' => $plan->year // Production happens in the SAME year
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process production details to extract timeline data
    if (!empty($productionDetails) && is_array($productionDetails)) {
        // Handle both array and object formats
        $weeklyProductions = [];
        
        if (isset($productionDetails['weekly_production']) && is_array($productionDetails['weekly_production'])) {
            $weeklyProductions = $productionDetails['weekly_production'];
        } elseif (is_array($productionDetails) && !isset($productionDetails['weekly_production'])) {
            $weeklyProductions = $productionDetails;
        }

        foreach ($weeklyProductions as $production) {
            if (isset($production['yield']) && $production['yield'] > 0) {
                $yield = floatval($production['yield']);
                $quarter = $production['quarter'] ?? '';
                $month = $production['month'] ?? '';
                $week = $production['week'] ?? '';

                // Add to total distributed yield
                $productionData['total_distributed_yield'] += $yield;

                // Add to quarter totals
                if ($quarter && isset($ethiopianMonths[$quarter])) {
                    if (!isset($productionData['quarters'][$quarter])) {
                        $productionData['quarters'][$quarter] = ['yield' => 0];
                    }
                    $productionData['quarters'][$quarter]['yield'] += $yield;
                }

                // Add to month totals
                if ($month) {
                    if (!isset($productionData['months'][$month])) {
                        $productionData['months'][$month] = ['yield' => 0];
                    }
                    $productionData['months'][$month]['yield'] += $yield;
                }

                // Add to week totals
                if ($week && $month) {
                    $weekKey = $month . ' ' . $week;
                    if (!isset($productionData['weeks'][$weekKey])) {
                        $productionData['weeks'][$weekKey] = ['yield' => 0];
                    }
                    $productionData['weeks'][$weekKey]['yield'] += $yield;
                }
            }
        }
    }

    // Create the transformed plan structure
    return [
        [
            'year' => $plan->year,
            'crop_type' => $plan->cropType->name ?? 'Unknown',
            'crop_name' => $plan->crop->name ?? 'Unknown',
            'unit' => 'qt',
            'expected_yield' => floatval($plan->expected_yield),
            'season' => $plan->season,
            'production_data' => $productionData
        ]
    ];
}

// production division end 


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

    // end of plan time 

    // production time 
    public function productiontime(Request $request)
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
        $productionplanDetails = $plan->productionplan_details ?? [];

        // Extract production timeline data from productionplan_details
        $productionData = $this->extractProductionTimelineData($productionplanDetails, $plan->year);

        return [
            'id' => $plan->id,
            'crop_type' => $plan->cropType->name ?? 'N/A',
            'crop_name' => $plan->crop->name ?? 'N/A',
            'unit' => 'Quintal',
            'previous_year' => $this->getPreviousYearData($plan),
            'productionplan_details' => $productionplanDetails,
            'production_data' => $productionData, // Extracted production timeline data
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

    return view('sector.crop_plans.productiontime', compact('cropPlans', 'transformedPlans'));
}

/**
 * Extract production timeline data from productionplan_details
 */
private function extractProductionTimelineData($productionplanDetails, $planYear)
{
    $productionData = [
        'production_year' => $planYear, // Production happens in the same year
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_yield' => 0,
        'remaining_yield' => 0
    ];

    if (empty($productionplanDetails)) {
        return $productionData;
    }

    // Handle different possible structures of productionplan_details
    if (is_array($productionplanDetails)) {
        // Check for production_year in the data
        if (isset($productionplanDetails['production_year'])) {
            $productionData['production_year'] = $productionplanDetails['production_year'];
        }

        // Structure 1: Direct array with quarters, months, weeks
        if (isset($productionplanDetails['quarters'])) {
            $productionData = array_merge($productionData, $this->extractProductionFromStructuredData($productionplanDetails));
        }
        // Structure 2: Array of weekly productions
        else if (isset($productionplanDetails[0]) && is_array($productionplanDetails[0])) {
            $productionData = array_merge($productionData, $this->extractFromWeeklyProduction($productionplanDetails));
        }
        // Structure 3: Legacy format with nested structure
        else if (isset($productionplanDetails['weekly_production'])) {
            $productionData = array_merge($productionData, $this->extractFromWeeklyProduction($productionplanDetails['weekly_production']));
        }

        // Also check for production_year in nested structures
        if (isset($productionplanDetails['production_year'])) {
            $productionData['production_year'] = $productionplanDetails['production_year'];
        }

        // Calculate total distributed yield
        $productionData['total_distributed_yield'] = array_reduce($productionData['quarters'], function($carry, $quarter) {
            return $carry + ($quarter['yield'] ?? 0);
        }, 0);
    }

    return $productionData;
}

/**
 * Extract from structured data with quarters, months, weeks for production
 */
private function extractProductionFromStructuredData($data)
{
    $productionData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_yield' => 0
    ];

    // Check for production_year in structured data
    if (isset($data['production_year'])) {
        $productionData['production_year'] = $data['production_year'];
    }

    // Extract quarters data
    if (isset($data['quarters']) && is_array($data['quarters'])) {
        foreach ($data['quarters'] as $quarterName => $quarterData) {
            if (isset($quarterData['yield']) && $quarterData['yield'] > 0) {
                $productionData['quarters'][$quarterName] = [
                    'yield' => $quarterData['yield'],
                    'checked' => $quarterData['checked'] ?? false
                ];
                $productionData['total_distributed_yield'] += $quarterData['yield'];
            }
        }
    }

    // Extract months data
    if (isset($data['months']) && is_array($data['months'])) {
        foreach ($data['months'] as $monthName => $monthData) {
            if (isset($monthData['yield']) && $monthData['yield'] > 0) {
                $productionData['months'][$monthName] = [
                    'yield' => $monthData['yield'],
                    'quarter' => $monthData['quarter'] ?? 'Unknown',
                    'checked' => $monthData['checked'] ?? false
                ];
            }
        }
    }

    // Extract weeks data
    if (isset($data['weeks']) && is_array($data['weeks'])) {
        foreach ($data['weeks'] as $weekKey => $weekData) {
            if (isset($weekData['yield']) && $weekData['yield'] > 0) {
                $productionData['weeks'][$weekKey] = [
                    'yield' => $weekData['yield'],
                    'month' => $weekData['month'] ?? 'Unknown',
                    'quarter' => $weekData['quarter'] ?? 'Unknown',
                    'week_number' => $weekData['week_number'] ?? 1,
                    'checked' => $weekData['checked'] ?? false
                ];
            }
        }
    }

    return $productionData;
}

/**
 * Extract from weekly production array
 */
private function extractFromWeeklyProduction($weeklyProduction)
{
    $productionData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_yield' => 0
    ];

    foreach ($weeklyProduction as $week) {
        if (is_array($week)) {
            $yield = $week['yield'] ?? 0;
            $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
            $month = $week['month'] ?? 'Unknown';
            $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);

            // Check for production_year in weekly data
            if (isset($week['production_year'])) {
                $productionData['production_year'] = $week['production_year'];
            }

            if ($yield > 0) {
                // Add to total
                $productionData['total_distributed_yield'] += $yield;

                // Track quarter
                if (!isset($productionData['quarters'][$quarter])) {
                    $productionData['quarters'][$quarter] = ['yield' => 0];
                }
                $productionData['quarters'][$quarter]['yield'] += $yield;

                // Track month
                if (!isset($productionData['months'][$month])) {
                    $productionData['months'][$month] = ['yield' => 0, 'quarter' => $quarter];
                }
                $productionData['months'][$month]['yield'] += $yield;

                // Track week
                $weekKey = $month . '_week_' . $weekNumber;
                $productionData['weeks'][$weekKey] = [
                    'yield' => $yield,
                    'month' => $month,
                    'quarter' => $quarter,
                    'week_number' => $weekNumber
                ];
            }
        }
    }

    return $productionData;
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






public function fertilizerTime(Request $request)
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
        $fertilizerDistributionDetails = $plan->fertilizer_distribution_details ?? [];

        // Extract timeline data from fertilizer_distribution_details
        $timelineData = $this->extractFertilizerTimelineData($fertilizerDistributionDetails, $plan->year);

        // Get fertilizer names and amounts properly
        $fertilizerList = $this->getFertilizerNamesAndAmounts($plan->fertilizer_details ?? []);

        return [
            'id' => $plan->id,
            'crop_type' => $plan->cropType->name ?? 'N/A',
            'crop_name' => $plan->crop->name ?? 'N/A',
            'unit' => 'Kilogram',
            'application_year' => $this->getApplicationYearData($plan),
            'fertilizer_distribution_details' => $fertilizerDistributionDetails,
            'timeline_data' => $timelineData,
            'fertilizer_details' => $plan->fertilizer_details ?? [],
            'fertilizer_list' => $fertilizerList, // Properly formatted fertilizer list
            'area_to_plant' => $plan->area_to_plant,
            'year' => $plan->year,
            'season' => $plan->season,
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

    return view('sector.fertilizers.fertilizertime', compact('cropPlans', 'transformedPlans'));
}

/**
 * Extract fertilizer names and amounts from fertilizer_details
 */
private function getFertilizerNamesAndAmounts($fertilizerDetails)
{
    $fertilizerList = [];
    
    if (empty($fertilizerDetails) || !is_array($fertilizerDetails)) {
        return $fertilizerList;
    }

    foreach ($fertilizerDetails as $fertDetail) {
        if (is_array($fertDetail)) {
            $fertilizerTypeId = $fertDetail['fertilizer_type_id'] ?? null;
            $fertilizerId = $fertDetail['fertilizer_id'] ?? null;
            $amount = $fertDetail['amount'] ?? 0;
            $unit = $fertDetail['unit'] ?? 'kg';
            
            // Get fertilizer name from database
            $fertilizerName = $this->getFertilizerName($fertilizerId, $fertilizerTypeId);
            
            if ($fertilizerName && $amount > 0) {
                $fertilizerList[] = [
                    'name' => $fertilizerName,
                    'amount' => $amount,
                    'unit' => $unit
                ];
            }
        }
    }

    return $fertilizerList;
}

/**
 * Get fertilizer name from database
 */
private function getFertilizerName($fertilizerId, $fertilizerTypeId = null)
{
    try {
        // Try to get fertilizer by ID first
        if ($fertilizerId) {
            $fertilizer = \App\Models\Fertilizer::find($fertilizerId);
            if ($fertilizer) {
                return $fertilizer->name;
            }
        }
        
        // If fertilizer not found by ID, try to get by fertilizer_type_id
        if ($fertilizerTypeId) {
            $fertilizerType = \App\Models\FertilizerType::find($fertilizerTypeId);
            if ($fertilizerType) {
                return $fertilizerType->name;
            }
        }
        
        return 'Unknown Fertilizer';
    } catch (\Exception $e) {
        return 'Unknown Fertilizer';
    }
}

/**
 * Extract fertilizer timeline data from fertilizer_distribution_details
 */
private function extractFertilizerTimelineData($fertilizerDistributionDetails, $planYear)
{
    $timelineData = [
        'application_year' => $planYear - 1, // Default fallback
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'fertilizer_breakdown' => []
    ];

    if (empty($fertilizerDistributionDetails)) {
        return $timelineData;
    }

    // Handle different possible structures of fertilizer_distribution_details
    if (is_array($fertilizerDistributionDetails)) {
        // Check for application_year in the data
        if (isset($fertilizerDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $fertilizerDistributionDetails['application_year'];
        }

        // Structure 1: Direct array with quarters, months, weeks
        if (isset($fertilizerDistributionDetails['quarters'])) {
            $timelineData = array_merge($timelineData, $this->extractFertilizerFromStructuredData($fertilizerDistributionDetails));
        }
        // Structure 2: Array of weekly distributions
        else if (isset($fertilizerDistributionDetails[0]) && is_array($fertilizerDistributionDetails[0])) {
            $timelineData = array_merge($timelineData, $this->extractFertilizerFromWeeklyDistribution($fertilizerDistributionDetails));
        }
        // Structure 3: Legacy format with nested structure
        else if (isset($fertilizerDistributionDetails['weekly_distribution'])) {
            $timelineData = array_merge($timelineData, $this->extractFertilizerFromWeeklyDistribution($fertilizerDistributionDetails['weekly_distribution']));
        }

        // Also check for application_year in nested structures
        if (isset($fertilizerDistributionDetails['application_year'])) {
            $timelineData['application_year'] = $fertilizerDistributionDetails['application_year'];
        }
    }

    return $timelineData;
}

/**
 * Extract from structured data with quarters, months, weeks for fertilizers
 */
private function extractFertilizerFromStructuredData($data)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'fertilizer_breakdown' => []
    ];

    // Check for application_year in structured data
    if (isset($data['application_year'])) {
        $timelineData['application_year'] = $data['application_year'];
    }

    // Extract quarters data
    if (isset($data['quarters']) && is_array($data['quarters'])) {
        foreach ($data['quarters'] as $quarterName => $quarterData) {
            if (isset($quarterData['quantity']) && $quarterData['quantity'] > 0) {
                $timelineData['quarters'][$quarterName] = [
                    'quantity' => $quarterData['quantity'],
                    'checked' => $quarterData['checked'] ?? false
                ];
                $timelineData['total_distributed_quantity'] += $quarterData['quantity'];
            }
        }
    }

    // Extract months data
    if (isset($data['months']) && is_array($data['months'])) {
        foreach ($data['months'] as $monthName => $monthData) {
            if (isset($monthData['quantity']) && $monthData['quantity'] > 0) {
                $timelineData['months'][$monthName] = [
                    'quantity' => $monthData['quantity'],
                    'quarter' => $monthData['quarter'] ?? 'Unknown',
                    'checked' => $monthData['checked'] ?? false
                ];
            }
        }
    }

    // Extract weeks data
    if (isset($data['weeks']) && is_array($data['weeks'])) {
        foreach ($data['weeks'] as $weekKey => $weekData) {
            if (isset($weekData['quantity']) && $weekData['quantity'] > 0) {
                $timelineData['weeks'][$weekKey] = [
                    'quantity' => $weekData['quantity'],
                    'month' => $weekData['month'] ?? 'Unknown',
                    'quarter' => $weekData['quarter'] ?? 'Unknown',
                    'week_number' => $weekData['week_number'] ?? 1,
                    'fertilizer_name' => $weekData['fertilizer_name'] ?? 'Unknown',
                    'unit' => $weekData['unit'] ?? 'kg',
                    'checked' => $weekData['checked'] ?? false
                ];
                
                // Track fertilizer breakdown
                $fertName = $weekData['fertilizer_name'] ?? 'Unknown';
                if (!isset($timelineData['fertilizer_breakdown'][$fertName])) {
                    $timelineData['fertilizer_breakdown'][$fertName] = [
                        'quantity' => 0,
                        'unit' => $weekData['unit'] ?? 'kg'
                    ];
                }
                $timelineData['fertilizer_breakdown'][$fertName]['quantity'] += $weekData['quantity'];
            }
        }
    }

    return $timelineData;
}

/**
 * Extract from weekly distribution array for fertilizers
 */
private function extractFertilizerFromWeeklyDistribution($weeklyDistribution)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'fertilizer_breakdown' => []
    ];

    foreach ($weeklyDistribution as $week) {
        if (is_array($week)) {
            $quantity = $week['quantity'] ?? 0;
            $quarter = $week['quarter'] ?? ($week['year'] ?? 'Unknown');
            $month = $week['month'] ?? 'Unknown';
            $weekNumber = $week['week'] ?? ($week['week_number'] ?? 1);
            $fertilizerName = $week['fertilizer_name'] ?? 'Unknown';
            $unit = $week['unit'] ?? 'kg';

            // Check for application_year in weekly data
            if (isset($week['application_year'])) {
                $timelineData['application_year'] = $week['application_year'];
            }

            if ($quantity > 0) {
                // Add to total
                $timelineData['total_distributed_quantity'] += $quantity;

                // Track quarter
                if (!isset($timelineData['quarters'][$quarter])) {
                    $timelineData['quarters'][$quarter] = ['quantity' => 0];
                }
                $timelineData['quarters'][$quarter]['quantity'] += $quantity;

                // Track month
                if (!isset($timelineData['months'][$month])) {
                    $timelineData['months'][$month] = ['quantity' => 0, 'quarter' => $quarter];
                }
                $timelineData['months'][$month]['quantity'] += $quantity;

                // Track week
                $weekKey = $month . '_week_' . $weekNumber;
                $timelineData['weeks'][$weekKey] = [
                    'quantity' => $quantity,
                    'month' => $month,
                    'quarter' => $quarter,
                    'week_number' => $weekNumber,
                    'fertilizer_name' => $fertilizerName,
                    'unit' => $unit
                ];

                // Track fertilizer breakdown
                if (!isset($timelineData['fertilizer_breakdown'][$fertilizerName])) {
                    $timelineData['fertilizer_breakdown'][$fertilizerName] = [
                        'quantity' => 0,
                        'unit' => $unit
                    ];
                }
                $timelineData['fertilizer_breakdown'][$fertilizerName]['quantity'] += $quantity;
            }
        }
    }

    return $timelineData;
}

/**
 * Get application year data for a crop plan
 */
private function getApplicationYearData($plan)
{
    return $plan->year - 1; // Application year is always planning year - 1
}

/**
 * Export fertilizer plans to Excel
 */
public function exportFertilizerExcel()
{
    $user = Auth::user();

    $cropPlans = KebeleCropPlan::where('kebele_id', $user->kebele_id)
        ->with(['cropType', 'crop'])
        ->orderBy('year', 'desc')
        ->orderBy('season')
        ->get();

    $fileName = 'fertilizer_plans_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function() use ($cropPlans) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Crop Type', 'Crop Name', 'Area to Plant', 'Year', 'Season', 
            'Fertilizer Types', 'Total Fertilizer Quantity', 'Responsible Staff'
        ]);

        // Data rows
        foreach ($cropPlans as $plan) {
            $fertilizerTypes = [];
            $totalFertilizer = 0;
            
            if ($plan->fertilizer_details && is_array($plan->fertilizer_details)) {
                foreach ($plan->fertilizer_details as $fert) {
                    $fertilizerTypes[] = $fert['fertilizer_name'] ?? 'Unknown';
                    $totalFertilizer += $fert['amount'] ?? 0;
                }
            }

            fputcsv($file, [
                $plan->cropType->name ?? 'N/A',
                $plan->crop->name ?? 'N/A',
                $plan->area_to_plant,
                $plan->year,
                $plan->season,
                implode(', ', $fertilizerTypes),
                $totalFertilizer,
                $plan->responsible_staff ?? 'N/A'
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
// end of fertlizer 



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







public function fertilizerPlan($id)
{
    $user = Auth::user();

    // Retrieve the plan; ensure user can only edit plans in their sector
    $plan = KebeleCropPlan::where('id', $id)
        ->where('kebele_id', $user->kebele_id)
        ->firstOrFail();

    $fertilizerTypes = FertilizerType::with('fertilizers')->get();

    // Transform the single plan data for fertilizer distribution table
    $transformedFertilizerPlans = $this->transformFertilizerPlanDataForTable($plan);

    return view('sector.fertilizers.fertilizerplan', compact('plan', 'fertilizerTypes', 'transformedFertilizerPlans'));
}

private function transformFertilizerPlanDataForTable($plan)
{
    // Extract fertilizer timeline data from plan
    $fertilizerDetails = $plan->fertilizer_details ?? [];
    
    // Initialize timeline data structure - application year is planning year - 1
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_quantity' => 0,
        'application_year' => $plan->year - 1 // Changed to planning year - 1
    ];

    // Define Ethiopian months by quarter
    $ethiopianMonths = [
        'Q1' => ['Meskerem', 'Tikimt', 'Hidar'],
        'Q2' => ['Tahsas', 'Tir', 'Yekatit'],
        'Q3' => ['Megabit', 'Miyazia', 'Ginbot'],
        'Q4' => ['Sene', 'Hamle', 'Nehase', 'Pagumen']
    ];

    // Process fertilizer details to extract timeline data
    if (!empty($fertilizerDetails) && is_array($fertilizerDetails)) {
        foreach ($fertilizerDetails as $distribution) {
            if (isset($distribution['quantity']) && $distribution['quantity'] > 0) {
                $quantity = floatval($distribution['quantity']);
                $quarter = $distribution['quarter'] ?? '';
                $month = $distribution['month'] ?? '';
                $week = $distribution['week'] ?? '';
                $fertilizerTypeId = $distribution['fertilizer_type_id'] ?? '';
                $fertilizerId = $distribution['fertilizer_id'] ?? '';
                $unit = $distribution['unit'] ?? 'kg';

                // Add to total distributed quantity
                $timelineData['total_distributed_quantity'] += $quantity;

                // Add to quarter totals
                if ($quarter && isset($ethiopianMonths[$quarter])) {
                    if (!isset($timelineData['quarters'][$quarter])) {
                        $timelineData['quarters'][$quarter] = ['quantity' => 0];
                    }
                    $timelineData['quarters'][$quarter]['quantity'] += $quantity;
                }

                // Add to month totals
                if ($month) {
                    if (!isset($timelineData['months'][$month])) {
                        $timelineData['months'][$month] = ['quantity' => 0];
                    }
                    $timelineData['months'][$month]['quantity'] += $quantity;
                }

                // Add to week totals
                if ($week && $month) {
                    $weekKey = $month . ' ' . $week;
                    if (!isset($timelineData['weeks'][$weekKey])) {
                        $timelineData['weeks'][$weekKey] = ['quantity' => 0];
                    }
                    $timelineData['weeks'][$weekKey]['quantity'] += $quantity;
                }
            }
        }
    }

    // Create the transformed fertilizer plan structure
    return [
        [
            'planning_year' => $plan->year,
            'application_year' => $plan->year - 1, // Added application year
            'crop_type' => $plan->cropType->name ?? 'Unknown',
            'crop_name' => $plan->crop->name ?? 'Unknown',
            'area_to_plant' => floatval($plan->area_to_plant),
            'season' => $plan->season,
            'fertilizer_details' => $fertilizerDetails,
            'timeline_data' => $timelineData
        ]
    ];
}

public function updateFertilizerDistribution(Request $request, $id)
{
    $user = Auth::user();
    $cropPlan = KebeleCropPlan::findOrFail($id);

    $validated = $request->validate([
        'fertilizer_distribution_details' => 'sometimes|json',
    ]);

    // Parse fertilizer distribution details
    $fertilizerDistributionDetails = json_decode($request->fertilizer_distribution_details, true) ?? [];

    // Update the crop plan with fertilizer distribution details
    $cropPlan->update([
        'fertilizer_distribution_details' => $fertilizerDistributionDetails,
        'updated_by' => $user->id,
    ]);

    return redirect()->route('sector.crop-plans.index')
        ->with('success', '✅ Fertilizer distribution plan updated successfully!');
}


}
