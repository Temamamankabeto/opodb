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

        return view('sector.crop_plans.index',
            compact(
                'cropPlans',
                'yearlyTotals',
                'grandTotalArea',
                'grandTotalYield',
                'cropTypes'));
    }


    public function rawwiiqonnaa()
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

        return view('sector.crop_plans.raawwiiqonnaa',
            compact(
                'cropPlans',
                'yearlyTotals',
                'grandTotalArea',
                'grandTotalYield',
                'cropTypes'));
    }


    public function rawwiiomisha()
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

        return view('sector.crop_plans.rawwiiomisha',
            compact(
                'cropPlans',
                'yearlyTotals',
                'grandTotalArea',
                'grandTotalYield',
                'cropTypes'));
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

     public function fertlizerachivement()
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

        return view('sector.fertilizers.fertlizerachivement', compact(
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

        $crops = $configs->map(function ($config) {
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


     public function achivement($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get();

        // Transform the single plan data for the table structure
        $transformedPlans = $this->transformPlanDataForTable($plan);

        return view('sector.crop_plans.achivement', compact('plan', 'cropTypes', 'transformedPlans'));
    }

    


    private function transformPlanDataForTable($plan)
    {
        // Extract timeline data from plan division details
        $planDivisionDetails = $plan->productionplan_details ?? [];

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

    public function updateProductionPlan(Request $request, $id)
{
    $user = Auth::user();
    $cropPlan = KebeleCropPlan::findOrFail($id);
    
    // Validate only the field we want to update
    $validated = $request->validate([
        'productionplan_details' => 'sometimes|json',
    ]);
    
    // Parse production plan details
    $productionplanDetails = json_decode($request->productionplan_details, true) ?? [];
    
    // Update ONLY the productionplan_details field
    $cropPlan->update([
        'productionplan_details' => $productionplanDetails,
        'updated_by' => $user->id,
    ]);
    
    return redirect()->route('sector.crop-karora.index')
        ->with('success', '✅ Production plan updated successfully!');
}


public function updateLandPreparation(Request $request, $id)
{
    $user = Auth::user();
    $cropPlan = KebeleCropPlan::findOrFail($id);
    
    // Validate only the field we want to update
    $validated = $request->validate([
        'plandivision_details' => 'sometimes|json',
    ]);
    
    // Parse land preparation details
    $plandivisionDetails = json_decode($request->plandivision_details, true) ?? [];
    
    // Update ONLY the plandivision_details field
    $cropPlan->update([
        'plandivision_details' => $plandivisionDetails,
        'updated_by' => $user->id,
    ]);
    
    return redirect()->route('sector.crop-karora.index')
        ->with('success', '✅ Land preparation plan updated successfully!');
}

// production division

    public function productionaachivement($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $cropTypes = CropType::with('crops')->get();

        // Transform the single plan data for production planning table structure
        $transformedPlans = $this->transformPlanDataForProductionTable($plan);

        return view('sector.crop_plans.productionaachivement', compact('plan', 'cropTypes', 'transformedPlans'));
    }

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

        // Extract timeline data for land preparation
        $timelineData = $this->extractLandPrepTimelineData($plandivisionDetails, $plan->year);

        return [
            'id' => $plan->id,
            'crop_type' => $plan->cropType->name ?? 'N/A',
            'crop_name' => $plan->crop->name ?? 'N/A',
            'unit' => 'Hectare',
            'area_to_plant' => $plan->area_to_plant,
            'expected_yield' => $plan->expected_yield,
            'year' => $plan->year,
            'season' => $plan->season,
            'seed_required' => $plan->seed_required,
            'responsible_staff' => $plan->responsible_staff,
            'notes' => $plan->notes,
            
            // Land preparation timeline data
            'timeline_data' => $timelineData,
            'preparation_year' => $plan->year - 1,
            'total_distributed_area' => $timelineData['total_distributed_area'] ?? 0,
            'quarters_data' => $timelineData['quarters'] ?? [],
            'months_data' => $timelineData['months'] ?? [],
            'weeks_data' => $timelineData['weeks'] ?? [],
            
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
 * Extract timeline data from plandivision_details for land preparation
 */
private function extractLandPrepTimelineData($plandivisionDetails, $planYear)
{
    $timelineData = [
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_area' => 0
    ];

    if (empty($plandivisionDetails) || !is_array($plandivisionDetails)) {
        return $timelineData;
    }

    // Handle the JSON structure from land preparation form
    if (isset($plandivisionDetails['quarters']) && is_array($plandivisionDetails['quarters'])) {
        foreach ($plandivisionDetails['quarters'] as $quarterData) {
            if (isset($quarterData['quarter'])) {
                $quarterNum = $quarterData['quarter'];
                $quarterName = 'Q' . $quarterNum;
                
                if (isset($quarterData['months']) && is_array($quarterData['months'])) {
                    foreach ($quarterData['months'] as $monthData) {
                        if (isset($monthData['month'])) {
                            $monthName = $monthData['month'];
                            
                            if (isset($monthData['weeks']) && is_array($monthData['weeks'])) {
                                foreach ($monthData['weeks'] as $weekData) {
                                    if (isset($weekData['week'])) {
                                        $weekNumber = $weekData['week'];
                                        
                                        $plannedArea = floatval($weekData['planned_area'] ?? 0);
                                        $achievedArea = floatval($weekData['achieved_area'] ?? 0);
                                        
                                        // Use planned area for distribution summary
                                        if ($plannedArea > 0) {
                                            $weekKey = $monthName . '_week_' . $weekNumber;
                                            
                                            // Add to week data
                                            $timelineData['weeks'][$weekKey] = [
                                                'planned_area' => $plannedArea,
                                                'achieved_area' => $achievedArea,
                                                'month' => $monthName,
                                                'quarter' => $quarterName,
                                                'week_number' => $weekNumber
                                            ];
                                            
                                            // Add to month totals
                                            if (!isset($timelineData['months'][$monthName])) {
                                                $timelineData['months'][$monthName] = [
                                                    'planned_area' => 0,
                                                    'achieved_area' => 0,
                                                    'quarter' => $quarterName
                                                ];
                                            }
                                            $timelineData['months'][$monthName]['planned_area'] += $plannedArea;
                                            $timelineData['months'][$monthName]['achieved_area'] += $achievedArea;
                                            
                                            // Add to quarter totals
                                            if (!isset($timelineData['quarters'][$quarterName])) {
                                                $timelineData['quarters'][$quarterName] = [
                                                    'planned_area' => 0,
                                                    'achieved_area' => 0
                                                ];
                                            }
                                            $timelineData['quarters'][$quarterName]['planned_area'] += $plannedArea;
                                            $timelineData['quarters'][$quarterName]['achieved_area'] += $achievedArea;
                                            
                                            // Add to total distributed
                                            $timelineData['total_distributed_area'] += $plannedArea;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // Handle legacy format (backward compatibility)
    else if (isset($plandivisionDetails['weekly_distribution']) && is_array($plandivisionDetails['weekly_distribution'])) {
        foreach ($plandivisionDetails['weekly_distribution'] as $weekItem) {
            if (is_array($weekItem)) {
                $plannedArea = floatval($weekItem['planned_area'] ?? $weekItem['area'] ?? 0);
                $achievedArea = floatval($weekItem['achieved_area'] ?? 0);
                $quarter = $weekItem['quarter'] ?? 'Unknown';
                $month = $weekItem['month'] ?? 'Unknown';
                
                // Extract week number
                $weekNumber = 1;
                if (isset($weekItem['week'])) {
                    if (is_numeric($weekItem['week'])) {
                        $weekNumber = intval($weekItem['week']);
                    } else if (preg_match('/Week\s*(\d+)/i', $weekItem['week'], $matches)) {
                        $weekNumber = intval($matches[1]);
                    }
                }
                
                if ($plannedArea > 0) {
                    $weekKey = $month . '_week_' . $weekNumber;
                    
                    // Add to week data
                    $timelineData['weeks'][$weekKey] = [
                        'planned_area' => $plannedArea,
                        'achieved_area' => $achievedArea,
                        'month' => $month,
                        'quarter' => $quarter,
                        'week_number' => $weekNumber
                    ];
                    
                    // Add to month totals
                    if (!isset($timelineData['months'][$month])) {
                        $timelineData['months'][$month] = [
                            'planned_area' => 0,
                            'achieved_area' => 0,
                            'quarter' => $quarter
                        ];
                    }
                    $timelineData['months'][$month]['planned_area'] += $plannedArea;
                    $timelineData['months'][$month]['achieved_area'] += $achievedArea;
                    
                    // Add to quarter totals
                    if (!isset($timelineData['quarters'][$quarter])) {
                        $timelineData['quarters'][$quarter] = [
                            'planned_area' => 0,
                            'achieved_area' => 0
                        ];
                    }
                    $timelineData['quarters'][$quarter]['planned_area'] += $plannedArea;
                    $timelineData['quarters'][$quarter]['achieved_area'] += $achievedArea;
                    
                    // Add to total distributed
                    $timelineData['total_distributed_area'] += $plannedArea;
                }
            }
        }
    }

    return $timelineData;
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

    // Transform data for export
    $exportData = $cropPlans->map(function ($plan) {
        $timelineData = $this->extractLandPrepTimelineData($plan->plandivision_details ?? [], $plan->year);
        
        return [
            'Crop Type' => $plan->cropType->name ?? 'N/A',
            'Crop Name' => $plan->crop->name ?? 'N/A',
            'Area to Plant (ha)' => $plan->area_to_plant,
            'Expected Yield (qt)' => $plan->expected_yield,
            'Year' => $plan->year,
            'Season' => $plan->season,
            'Preparation Year' => $plan->year - 1,
            'Seed Required (kg)' => $plan->seed_required,
            'Responsible Staff' => $plan->responsible_staff ?? 'N/A',
            'Total Distributed Area (ha)' => $timelineData['total_distributed_area'] ?? 0,
            'Remaining Area (ha)' => $plan->area_to_plant - ($timelineData['total_distributed_area'] ?? 0),
            'Last Updated' => $plan->updated_at->format('Y-m-d H:i:s')
        ];
    });

    $fileName = 'land_preparation_plans_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () use ($exportData) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, array_keys($exportData->first()));

        // Data rows
        foreach ($exportData as $row) {
            fputcsv($file, $row);
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
            'area_to_plant' => $plan->area_to_plant,
            'expected_yield' => $plan->expected_yield,
            'year' => $plan->year,
            'season' => $plan->season,
            'seed_required' => $plan->seed_required,
            'responsible_staff' => $plan->responsible_staff,
            'notes' => $plan->notes,
            
            // Production timeline data
            'productionplan_details' => $productionplanDetails,
            'production_data' => $productionData,
            'production_year' => $productionData['production_year'] ?? $plan->year,
            'total_distributed_yield' => $productionData['total_distributed_yield'] ?? 0,
            'quarters_data' => $productionData['quarters'] ?? [],
            'months_data' => $productionData['months'] ?? [],
            'weeks_data' => $productionData['weeks'] ?? [],
            
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
 * Based on your form's storage structure
 */
private function extractProductionTimelineData($productionplanDetails, $planYear)
{
    $timelineData = [
        'production_year' => $planYear,
        'quarters' => [],
        'months' => [],
        'weeks' => [],
        'total_distributed_yield' => 0
    ];

    if (empty($productionplanDetails) || !is_array($productionplanDetails)) {
        return $timelineData;
    }

    // Check for production_year in the data
    if (isset($productionplanDetails['year'])) {
        $timelineData['production_year'] = $productionplanDetails['year'];
    }

    // Handle your storage structure
    if (isset($productionplanDetails['quarters']) && is_array($productionplanDetails['quarters'])) {
        foreach ($productionplanDetails['quarters'] as $quarterData) {
            if (isset($quarterData['quarter'])) {
                $quarterNum = $quarterData['quarter'];
                $quarterName = 'Q' . $quarterNum;
                $quarterTotal = 0;
                
                if (isset($quarterData['months']) && is_array($quarterData['months'])) {
                    foreach ($quarterData['months'] as $monthData) {
                        if (isset($monthData['month'])) {
                            $monthName = $monthData['month'];
                            $monthTotal = 0;
                            
                            if (isset($monthData['weeks']) && is_array($monthData['weeks'])) {
                                foreach ($monthData['weeks'] as $weekData) {
                                    if (isset($weekData['week'])) {
                                        $weekNumber = $weekData['week'];
                                        $yield = floatval($weekData['planned'] ?? 0);
                                        
                                        if ($yield > 0) {
                                            $weekKey = $monthName . '_week_' . $weekNumber;
                                            
                                            // Add to week data
                                            $timelineData['weeks'][$weekKey] = [
                                                'yield' => $yield,
                                                'month' => $monthName,
                                                'quarter' => $quarterName,
                                                'week_number' => $weekNumber
                                            ];
                                            
                                            // Update month total
                                            $monthTotal += $yield;
                                            
                                            // Update quarter total
                                            $quarterTotal += $yield;
                                            
                                            // Add to total distributed
                                            $timelineData['total_distributed_yield'] += $yield;
                                        }
                                    }
                                }
                            }
                            
                            // Add month data if there's any yield
                            if ($monthTotal > 0) {
                                $timelineData['months'][$monthName] = [
                                    'yield' => $monthTotal,
                                    'quarter' => $quarterName
                                ];
                            }
                        }
                    }
                }
                
                // Add quarter data if there's any yield
                if ($quarterTotal > 0) {
                    $timelineData['quarters'][$quarterName] = [
                        'yield' => $quarterTotal
                    ];
                }
            }
        }
    }

    return $timelineData;
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

        // Calculate total available fertilizer
        $totalAvailableFertilizer = collect($fertilizerList)->sum('amount');

        return [
            'id' => $plan->id,
            'crop_type' => $plan->cropType->name ?? 'N/A',
            'crop_name' => $plan->crop->name ?? 'N/A',
            'unit' => 'Kilogram',
            'application_year' => $timelineData['application_year'] ?? $plan->year - 1,
            'fertilizer_distribution_details' => $fertilizerDistributionDetails,
            
            // Use consistent naming like land preparation
            'timeline_data' => $timelineData,
            'total_distributed_quantity' => $timelineData['total_distributed_quantity'] ?? 0,
            'quarters_data' => $timelineData['quarters'] ?? [],
            'months_data' => $timelineData['months'] ?? [],
            'weeks_data' => $timelineData['weeks'] ?? [],
            
            'fertilizer_details' => $plan->fertilizer_details ?? [],
            'fertilizer_list' => $fertilizerList,
            'total_available_fertilizer' => $totalAvailableFertilizer,
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
            $fertilizerId = $fertDetail['fertilizer_id'] ?? null;
            $fertilizerTypeId = $fertDetail['fertilizer_type_id'] ?? null;
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
 * Based on your form's storage structure
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

    if (empty($fertilizerDistributionDetails) || !is_array($fertilizerDistributionDetails)) {
        return $timelineData;
    }

    // Check for year in the data
    if (isset($fertilizerDistributionDetails['year'])) {
        $timelineData['application_year'] = $fertilizerDistributionDetails['year'];
    }

    // Handle your storage structure
    if (isset($fertilizerDistributionDetails['quarters']) && is_array($fertilizerDistributionDetails['quarters'])) {
        foreach ($fertilizerDistributionDetails['quarters'] as $quarterData) {
            if (isset($quarterData['quarter'])) {
                $quarterNum = $quarterData['quarter'];
                $quarterName = 'Q' . $quarterNum;
                $quarterTotal = 0;
                
                if (isset($quarterData['months']) && is_array($quarterData['months'])) {
                    foreach ($quarterData['months'] as $monthData) {
                        if (isset($monthData['month'])) {
                            $monthName = $monthData['month'];
                            $monthTotal = 0;
                            
                            if (isset($monthData['weeks']) && is_array($monthData['weeks'])) {
                                foreach ($monthData['weeks'] as $weekData) {
                                    if (isset($weekData['week'])) {
                                        $weekNumber = $weekData['week'];
                                        
                                        // Process fertilizers for this week
                                        if (isset($weekData['fertilizers']) && is_array($weekData['fertilizers'])) {
                                            foreach ($weekData['fertilizers'] as $fertData) {
                                                $quantity = floatval($fertData['planned_value'] ?? 0);
                                                
                                                if ($quantity > 0) {
                                                    $weekKey = $monthName . '_week_' . $weekNumber;
                                                    
                                                    // Add to week data
                                                    if (!isset($timelineData['weeks'][$weekKey])) {
                                                        $timelineData['weeks'][$weekKey] = [
                                                            'quantity' => 0,
                                                            'month' => $monthName,
                                                            'quarter' => $quarterName,
                                                            'week_number' => $weekNumber,
                                                            'fertilizers' => []
                                                        ];
                                                    }
                                                    
                                                    $timelineData['weeks'][$weekKey]['quantity'] += $quantity;
                                                    $timelineData['weeks'][$weekKey]['fertilizers'][] = [
                                                        'name' => $fertData['name'] ?? 'Unknown',
                                                        'quantity' => $quantity,
                                                        'unit' => $fertData['unit'] ?? 'kg'
                                                    ];
                                                    
                                                    // Update month total
                                                    $monthTotal += $quantity;
                                                    
                                                    // Update quarter total
                                                    $quarterTotal += $quantity;
                                                    
                                                    // Add to total distributed
                                                    $timelineData['total_distributed_quantity'] += $quantity;
                                                    
                                                    // Track fertilizer breakdown
                                                    $fertName = $fertData['name'] ?? 'Unknown';
                                                    if (!isset($timelineData['fertilizer_breakdown'][$fertName])) {
                                                        $timelineData['fertilizer_breakdown'][$fertName] = [
                                                            'quantity' => 0,
                                                            'unit' => $fertData['unit'] ?? 'kg'
                                                        ];
                                                    }
                                                    $timelineData['fertilizer_breakdown'][$fertName]['quantity'] += $quantity;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                            // Add month data if there's any quantity
                            if ($monthTotal > 0) {
                                $timelineData['months'][$monthName] = [
                                    'quantity' => $monthTotal,
                                    'quarter' => $quarterName
                                ];
                            }
                        }
                    }
                }
                
                // Add quarter data if there's any quantity
                if ($quarterTotal > 0) {
                    $timelineData['quarters'][$quarterName] = [
                        'quantity' => $quarterTotal
                    ];
                }
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

    $callback = function () use ($cropPlans) {
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

     public function fertilizerachivement($id)
    {
        $user = Auth::user();

        // Retrieve the plan; ensure user can only edit plans in their sector
        $plan = KebeleCropPlan::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $fertilizerTypes = FertilizerType::with('fertilizers')->get();

        // Transform the single plan data for fertilizer distribution table
        $transformedFertilizerPlans = $this->transformFertilizerPlanDataForTable($plan);

        return view('sector.fertilizers.fertilizerachivement', compact('plan', 'fertilizerTypes', 'transformedFertilizerPlans'));
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
    
    // Validate only the field we want to update
    $validated = $request->validate([
        'fertilizer_distribution_details' => 'sometimes|json',
    ]);
    
    // Parse fertilizer distribution details
    $fertilizerDistributionDetails = json_decode($request->fertilizer_distribution_details, true) ?? [];
    
    // Update ONLY the fertilizer_distribution_details field
    $cropPlan->update([
        'fertilizer_distribution_details' => $fertilizerDistributionDetails,
        'updated_by' => $user->id,
    ]);
    
    return redirect()->route('sector.crop-karora.index')
        ->with('success', '✅ Fertilizer distribution plan updated successfully!');
}

}
